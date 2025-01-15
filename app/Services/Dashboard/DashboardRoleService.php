<?php

namespace App\Services\Dashboard;

use App\Interfaces\PermissionInterface;
use App\Interfaces\RoleInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardRoleService
{
    protected $roleRepository;
    protected $permissionRepository;

    public function __construct(RoleInterface $roleRepository, PermissionInterface $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedPermissions(array $filters, int $perPage = 10)
    {
        return $this->roleRepository->query()
            ->when($filters['search'] ?? null, fn($query, $search) => $query->where('name', 'like', "%$search%"))
            ->orderBy($filters['sort_by'] ?? 'id', $filters['sort_direction'] ?? 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Create record
     * @param array $data
     * @return mixed
     */
    public function createRole(array $data)
    {
        try {
            DB::beginTransaction();
            $role = $this->roleRepository->create($data);
            $permissions = $this->permissionRepository->getByIds($data['permissions']);
            $role->syncPermissions($permissions);
            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create role: ' . $e->getMessage());

            throw new \RuntimeException('Could not create role');
        }
    }


    /**
     * Update record
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateRole($id, array $data)
    {
        try {
            DB::beginTransaction();
            $role = $this->roleRepository->update($id, $data);
            $permissions = $this->permissionRepository->getByIds($data['permissions']);
            $role->syncPermissions($permissions);
            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update role: ' . $e->getMessage());

            throw new \RuntimeException('Could not update role', $e);
        }
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deleteRole($id)
    {
        try {
            DB::beginTransaction();
            $result = $this->roleRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete role: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete role');
        }
    }
}
