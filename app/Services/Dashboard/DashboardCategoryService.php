<?php

namespace App\Services\Dashboard;

use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardCategoryService
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedCategories(array $filters, int $perPage = 10): mixed
    {
        return $this->categoryRepository->query()
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
    public function createCategory(array $data): mixed
    {
        try {
            DB::beginTransaction();
            $model = $this->categoryRepository->create($data);

            $services = $data['services'] ?? null;
            if($services) {
                $model->services()->sync($data['services']);
            }


            DB::commit();

            return $model;
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            Log::error('Failed to create category: ' . $e->getMessage());

            throw new \RuntimeException('Could not create category');
        }
    }

    /**
     * Update record
     * @param $id
     * @param mixed $data
     * @return mixed
     */
    public function updateCategory($id, mixed $data): mixed
    {
        try {
            DB::beginTransaction();
            $category = $this->categoryRepository->find($id);
            $category->fill($data);
            $category->save();

            $services = $data['services'] ?? null;
            if($services) {
                $category->services()->sync($data['services']);
            }


            DB::commit();

            return $category;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update category: ' . $e->getMessage());

            throw new \RuntimeException('Could not update category', $e);
        }
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deleteCategory($id): mixed
    {
        try {
            DB::beginTransaction();
            $result = $this->categoryRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete category: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete category');
        }
    }
}
