<?php

namespace App\Services\Dashboard;

use App\Repositories\LanguageRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardLanguageService
{
    protected $languageRepository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedLanguages(array $filters, int $perPage = 10)
    {
        return $this->languageRepository->query()
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
    public function createLanguage(array $data)
    {
        try {
            DB::beginTransaction();
            $model = $this->languageRepository->create($data);
            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create language: ' . $e->getMessage());

            throw new \RuntimeException('Could not create language');
        }
    }

    /**
     * Update record
     * @param $id
     * @param mixed $data
     * @return mixed
     */
    public function updateLanguage($id, mixed $data)
    {
        try {
            DB::beginTransaction();
            $language = $this->languageRepository->find($id);
            $language->fill($data);
            $language->save();
            DB::commit();

            return $language;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update language: ' . $e->getMessage());

            throw new \RuntimeException('Could not update language', $e);
        }
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deleteLanguage($id)
    {
        try {
            DB::beginTransaction();
            $result = $this->languageRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete language: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete language');
        }
    }
}
