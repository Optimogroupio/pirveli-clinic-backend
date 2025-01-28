<?php

namespace App\Services\Dashboard;

use App\Repositories\ServiceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardServiceService
{
    protected $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedServices(array $filters, int $perPage = 10): mixed
    {
        return $this->serviceRepository->query()
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
    public function createService(array $data): mixed
    {
        try {
            DB::beginTransaction();
            $model = $this->serviceRepository->create($data);
            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create service: ' . $e->getMessage());

            throw new \RuntimeException('Could not create service');
        }
    }

    /**
     * Update record
     * @param $id
     * @param mixed $data
     * @return mixed
     */
    public function updateService($id, mixed $data): mixed
    {
        try {
            DB::beginTransaction();
            $service = $this->serviceRepository->find($id);
            $service->fill($data);
            $service->save();
            DB::commit();

            return $service;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update service: ' . $e->getMessage());

            throw new \RuntimeException('Could not update service', $e);
        }
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deleteService($id): mixed
    {
        try {
            DB::beginTransaction();
            $result = $this->serviceRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete service: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete service');
        }
    }
}
