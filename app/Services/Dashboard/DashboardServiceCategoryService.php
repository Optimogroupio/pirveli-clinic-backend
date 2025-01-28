<?php

namespace App\Services\Dashboard;

use App\Repositories\ServiceCategoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardServiceCategoryService
{
    protected $serviceCategoryRepository;

    public function __construct(ServiceCategoryRepository $serviceCategoryRepository)
    {
        $this->serviceCategoryRepository = $serviceCategoryRepository;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedPermissions(array $filters, int $perPage = 10)
    {
        return $this->serviceCategoryRepository->query()
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
    public function createServiceCategory(array $data)
    {
        try {
            DB::beginTransaction();
            $model = $this->serviceCategoryRepository->create($data);
            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create service category: ' . $e->getMessage());

            throw new \RuntimeException('Could not create service category');
        }
    }

    /**
     * Update record
     * @param $id
     * @param mixed $data
     * @return mixed
     */
    public function updateServiceCategory($id, mixed $data): mixed
    {
        try {
            DB::beginTransaction();
            $serviceCategory = $this->serviceCategoryRepository->find($id);
            $serviceCategory->fill($data); // Fill default locale data
            $serviceCategory->save();
            DB::commit();

            return $serviceCategory;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update service category: ' . $e->getMessage());

            throw new \RuntimeException('Could not update service category', $e);
        }
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deleteServiceCategory($id): mixed
    {
        try {
            DB::beginTransaction();
            $result = $this->serviceCategoryRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete service category: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete service category');
        }
    }
}
