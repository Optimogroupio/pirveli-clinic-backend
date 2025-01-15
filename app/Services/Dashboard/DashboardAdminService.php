<?php

namespace App\Services\Dashboard;

use App\Interfaces\AdminInterface;
use App\Interfaces\RoleInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DashboardAdminService
{
    protected $adminRepository;
    protected $roleRepository;

    public function __construct(AdminInterface $adminRepository, RoleInterface $roleRepository)
    {
        $this->adminRepository = $adminRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedPermissions(array $filters, int $perPage = 10)
    {
        return $this->adminRepository->query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('login', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            })
            ->orderBy($filters['sort_by'] ?? 'id', $filters['sort_direction'] ?? 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Create record
     * @param array $data
     * @return mixed
     */
    public function createAdmin(array $data)
    {
        DB::beginTransaction();

        try {

            $password = $data['password'] ?? null;
            $roles = $data['roles'] ?? null;

            if ($password) {
                $data['password'] = Hash::make($data['password']);
            }

            $user = $this->adminRepository->create($data);

            if ($roles) {
                $roles = $this->roleRepository->getByIds($roles);
                $user->syncRoles($roles);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create administrator: ' . $e->getMessage());

            throw new \RuntimeException('Could not create administrator');
        }

        DB::commit();
    }

    /**
     * Update record
     * @param array $data
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function updateAdmin(int $id, array $data): void
    {
        DB::beginTransaction();

        try {
            $password = $data['password'] ?? null;
            $roles = $data['roles'] ?? null;

            if ($password) {
                $data['password'] = Hash::make($data['password']);
            }
            $this->adminRepository->update($id, $data);
            $administrator = $this->adminRepository->find($id);

            if ($roles) {
                $roles = $this->roleRepository->getByIds($roles);
                $administrator->syncRoles($roles);
            } else {
                $administrator->syncRoles([]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deleteAdmin($id)
    {
        try {
            DB::beginTransaction();
            $result = $this->adminRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete administrator: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete administrator');
        }
    }
}
