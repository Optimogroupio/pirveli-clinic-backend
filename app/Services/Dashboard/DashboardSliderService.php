<?php

namespace App\Services\Dashboard;

use App\Repositories\SliderRepository;
use App\Services\AttachmentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardSliderService
{
    protected $sliderRepository;
    protected $attachmentService;

    public function __construct(SliderRepository $sliderRepository, AttachmentService $attachmentService)
    {
        $this->sliderRepository = $sliderRepository;
        $this->attachmentService = $attachmentService;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedSlider(array $filters, int $perPage = 10): mixed
    {
        return $this->sliderRepository->query()
            ->with('image')
            ->when($filters['search'] ?? null, fn($query, $search) => $query->where('title', 'like', "%$search%"))
            ->orderBy($filters['sort_by'] ?? 'sort_order', $filters['sort_direction'] ?? 'asc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Create record
     * @param array $data
     * @return mixed
     */
    public function createSlider(array $data): mixed
    {
        try {
            DB::beginTransaction();
            $model = $this->sliderRepository->create($data);

            $file = $data['image'] ?? null;
            unset($data['image']);

            if ($file) {
                $this->attachmentService->attachFile($model, $file, 'image');
            }

            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create slider: ' . $e->getMessage());

            throw new \RuntimeException('Could not create slider');
        }
    }

    /**
     * Update record
     * @param $id
     * @param mixed $data
     * @return mixed
     */
    public function updateSlider($id, mixed $data): mixed
    {
        try {
            DB::beginTransaction();
            $slider = $this->sliderRepository->find($id);
            $slider->fill($data);
            $slider->save();

            $image = $data['image'] ?? null;
            unset($data['image']);

            if($image && $image instanceof UploadedFile){
                $this->attachmentService->deleteAttachment($slider->image);
                $this->attachmentService->attachFile($slider, $data['image'], 'image');
            }

            DB::commit();

            return $slider;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update slider: ' . $e->getMessage());

            throw new \RuntimeException('Could not update slider', $e);
        }
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deleteSlider($id): mixed
    {
        try {
            DB::beginTransaction();
            $result = $this->sliderRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete slider: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete slider');
        }
    }

    /**
     * Update the order of the slider
     *
     * @param array $data
     * @return bool
     * @throws \RuntimeException
     */
    public function updateOrder(array $data): bool
    {
        try {
            DB::beginTransaction();

            foreach ($data['orderedIds'] as $detail) {
                $model = $this->sliderRepository->find($detail['id']);

                if (!$model) {
                    throw new \RuntimeException("Slider with ID {$detail['id']} not found.");
                }

                $model->update(['sort_order' => $detail['order']]);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update Slider order: ' . $e->getMessage());
            throw new \RuntimeException('Could not update slider order');
        }
    }
}
