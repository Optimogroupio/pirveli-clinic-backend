<?php

namespace App\Services\Dashboard;

use App\Repositories\PageRepository;
use App\Services\AttachmentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardPageService
{
    protected $pageRepository;
    protected $attachmentService;

    public function __construct(PageRepository $pageRepository, AttachmentService $attachmentService)
    {
        $this->pageRepository = $pageRepository;
        $this->attachmentService = $attachmentService;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedPages(array $filters, int $perPage = 10): mixed
    {
        return $this->pageRepository->query()
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
    public function createPage(array $data): mixed
    {
        try {
            DB::beginTransaction();
            $model = $this->pageRepository->create($data);

            $file = $data['image'] ?? null;
            unset($data['image']);

            if ($file) {
                $this->attachmentService->attachFile($model, $file, 'image');
            }

            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create page: ' . $e->getMessage());

            throw new \RuntimeException('Could not create page');
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
            $page = $this->pageRepository->find($id);
            $page->fill($data);
            $page->save();

            $image = $data['image'] ?? null;
            unset($data['image']);

            if($image instanceof UploadedFile){
                if($page->image){
                    $this->attachmentService->deleteAttachment($page->image);
                }
                $this->attachmentService->attachFile($page, $image, 'image');
            }

            DB::commit();

            return $service;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update page: ' . $e->getMessage());

            throw new \RuntimeException('Could not update page', $e);
        }
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deletePage($id): mixed
    {
        try {
            DB::beginTransaction();
            $result = $this->pageRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete page: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete page');
        }
    }
}
