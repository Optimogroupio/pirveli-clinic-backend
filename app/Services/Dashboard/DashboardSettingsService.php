<?php

namespace App\Services\Dashboard;

use App\Repositories\SettingsRepository;
use App\Services\AttachmentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardSettingsService
{
    protected $settingsRepository;
    protected $attachmentService;

    public function __construct(SettingsRepository $settingsRepositorye, AttachmentService $attachmentService)
    {
        $this->settingsRepository = $settingsRepositorye;
        $this->attachmentService = $attachmentService;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedSettings(array $filters, int $perPage = 10): mixed
    {
        return $this->settingsRepository->query()
            ->with(['translations','banner_image', 'logo'])
            ->when($filters['search'] ?? null, fn($query, $search) => $query->where('key', 'like', "%$search%"))
            ->orderBy($filters['sort_by'] ?? 'id', $filters['sort_direction'] ?? 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Create record
     * @param array $data
     * @return mixed
     */
    public function createSettings(array $data): mixed
    {
        try {
            DB::beginTransaction();
            $model = $this->settingsRepository->create($data);

            $banner_image = $data['banner_image'] ?? null;
            $logo = $data['logo'] ?? null;

            unset($data['banner_image']);
            unset($data['logo']);

            if ($banner_image || $logo) {
                $this->attachmentService->attachFile($model, $banner_image ?? $logo, $banner_image ? 'banner_image' : 'logo');
            }

            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create settings: ' . $e->getMessage());

            throw new \RuntimeException('Could not create settings');
        }
    }

    /**
     * Update record
     * @param $id
     * @param mixed $data
     * @return mixed
     */
    public function updateSettings($id, mixed $data): mixed
    {
        try {
            DB::beginTransaction();
            $settings = $this->settingsRepository->find($id);
            $settings->fill($data);
            $settings->save();

            $banner_image = $data['banner_image'] ?? null;
            $logo = $data['logo'] ?? null;

            unset($data['banner_image']);
            unset($data['logo']);

            if($banner_image && $banner_image instanceof UploadedFile){
                $this->attachmentService->deleteAttachment($settings->image);
                $this->attachmentService->attachFile($settings, $data['banner_image'], 'banner_image');
            }

            if($logo && $logo instanceof UploadedFile){
                $this->attachmentService->deleteAttachment($settings->image);
                $this->attachmentService->attachFile($settings, $data['logo'], 'logo');
            }

            DB::commit();

            return $settings;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update settings: ' . $e->getMessage());

            throw new \RuntimeException('Could not update settings', $e);
        }
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deleteSettings($id): mixed
    {
        try {
            DB::beginTransaction();
            $result = $this->settingsRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete settings: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete settings');
        }
    }
}
