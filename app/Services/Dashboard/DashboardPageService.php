<?php

namespace App\Services\Dashboard;

use App\Repositories\PageRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardPageService
{
    protected $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedPages(array $filters, int $perPage = 10)
    {
        return $this->pageRepository->query()
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
    public function createPage(array $data)
    {
        try {
            DB::beginTransaction();
            $model = $this->pageRepository->create($data);
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
    public function updateService($id, mixed $data)
    {
        try {
            DB::beginTransaction();
            $service = $this->pageRepository->find($id);
            $service->fill($data);
            $service->save();
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
    public function deletePage($id)
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
