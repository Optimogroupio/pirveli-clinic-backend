<?php

namespace App\Services\Dashboard;

use App\Models\Page;
use App\Repositories\NewsRepository;
use App\Services\AttachmentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardNewsService
{
    protected $newsRepository;
    protected $attachmentService;

    public function __construct(NewsRepository $newsRepository, AttachmentService $attachmentService)
    {
        $this->newsRepository = $newsRepository;
        $this->attachmentService = $attachmentService;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedNews(array $filters, int $perPage = 10): mixed
    {
        return $this->newsRepository->query()
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
    public function createNews(array $data): mixed
    {
        try {
            DB::beginTransaction();
            $model = $this->newsRepository->create($data);

            $file = $data['image'] ?? null;
            unset($data['image']);

            if ($file) {
                $this->attachmentService->attachFile($model, $file, 'image');
            }

            $model->doctors()->sync($data['doctors']);

            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create news: ' . $e->getMessage());

            throw new \RuntimeException('Could not create news');
        }
    }

    /**
     * Update record
     * @param $id
     * @param mixed $data
     * @return mixed
     */
    public function updateNews($id, mixed $data): mixed
    {
        try {

            DB::beginTransaction();
            $news = $this->newsRepository->find($id);
            $news->fill($data);
            $news->save();

            $doctors = $data['doctors'] ?? null;

            if($doctors){
                $news->doctors()->sync($data['doctors']);
            }

            $image = $data['image'] ?? null;
            unset($data['image']);

            if($image instanceof UploadedFile){
                if($news->image){
                    $this->attachmentService->deleteAttachment($news->image);
                }
                $this->attachmentService->attachFile($news, $image, 'image');
            }

            DB::commit();

            return $news;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update news: ' . $e->getMessage());

            throw new \RuntimeException('Could not update news', $e);
        }
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deleteNews($id): mixed
    {
        try {
            DB::beginTransaction();
            $result = $this->newsRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete news: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete news');
        }
    }
}
