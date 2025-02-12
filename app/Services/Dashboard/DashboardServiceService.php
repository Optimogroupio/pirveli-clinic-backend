<?php

namespace App\Services\Dashboard;

use App\Repositories\ServiceRepository;
use App\Services\AttachmentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardServiceService
{
    protected $serviceRepository;
    protected $attachmentService;

    public function __construct(ServiceRepository $serviceRepository, AttachmentService $attachmentService)
    {
        $this->serviceRepository = $serviceRepository;
        $this->attachmentService = $attachmentService;
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
            ->with('image')
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

            $file = $data['image'] ?? null;
            unset($data['image']);

            if ($file) {
                $this->attachmentService->attachFile($model, $file, 'image');
            }

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

            $image = $data['image'] ?? null;
            unset($data['image']);

            if($image instanceof UploadedFile){
                if($service->image){
                    $this->attachmentService->deleteAttachment($service->image);
                }
                $this->attachmentService->attachFile($service, $image, 'image');
            }

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
