<?php

namespace App\Services\Dashboard;

use App\Interfaces\PermissionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardPermissionService
{
    protected $permissionRepository;

    public function __construct(PermissionInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedPermissions(array $filters, int $perPage = 10): mixed
    {
        return $this->permissionRepository->query()
            ->when($filters['search'] ?? null, fn($query, $search) => $query->where('name', 'like', "%$search%"))
            ->orderBy($filters['sort_by'] ?? 'id', $filters['sort_direction'] ?? 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Get all records
     * @return mixed
     */
    public function getAllPermissions(): mixed
    {
        return $this->permissionRepository->all();
    }

    /**
     * Get single record
     * @param $id
     * @return mixed
     */
    public function getPermissionById($id): mixed
    {
        return $this->permissionRepository->find($id);
    }

    /**
     * Store data to database
     * @param array $data
     * @return mixed
     */
    public function createPermission(array $data): mixed
    {
        try {
            DB::beginTransaction();
            $result = $this->permissionRepository->create($data);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create permission: ' . $e->getMessage());

            throw new \RuntimeException('Could not create permission');
        }
    }

    /**
     * Update existing record
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updatePermission($id, array $data): mixed
    {
        try {
            DB::beginTransaction();
            $result = $this->permissionRepository->update($id, $data);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update permission: ' . $e->getMessage());

            throw new \RuntimeException('Could not update permission');
        }
    }

    /**
     * Delete record
     * @param int $id
     * @return mixed
     */
    public function deletePermission(int $id): mixed
    {
        try {
            DB::beginTransaction();
            $result = $this->permissionRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete permission: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete permission');
        }
    }
}
