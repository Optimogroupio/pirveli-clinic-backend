<?php

namespace App\Services\Dashboard;

use App\Repositories\SpecialtyRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardSpecialtyService
{
    protected $specialtyRepository;

    public function __construct(SpecialtyRepository $specialtyRepository)
    {
        $this->specialtyRepository = $specialtyRepository;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedSpecialties(array $filters, int $perPage = 10)
    {
        return $this->specialtyRepository->query()
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
    public function createSpecialty(array $data)
    {
        try {
            DB::beginTransaction();
            $model = $this->specialtyRepository->create($data);
            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create specialty: ' . $e->getMessage());

            throw new \RuntimeException('Could not create specialty');
        }
    }

    /**
     * Update record
     * @param $id
     * @param mixed $data
     * @return mixed
     */
    public function updateSpecialty($id, mixed $data)
    {
        try {
            DB::beginTransaction();
            $language = $this->specialtyRepository->find($id);
            $language->fill($data);
            $language->save();
            DB::commit();

            return $language;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update specialty: ' . $e->getMessage());

            throw new \RuntimeException('Could not update specialty', $e);
        }
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deleteSpecialty($id)
    {
        try {
            DB::beginTransaction();
            $result = $this->specialtyRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete specialty: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete specialty');
        }
    }
}
