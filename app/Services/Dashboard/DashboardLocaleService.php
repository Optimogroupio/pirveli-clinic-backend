<?php

namespace App\Services\Dashboard;

use App\Interfaces\LocaleInterface;
use App\Models\Locale;
use App\Repositories\LocaleRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardLocaleService
{
    protected $localeRepository;

    public function __construct(LocaleRepository $localeRepository)
    {
        $this->localeRepository = $localeRepository;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedPermissions(array $filters, int $perPage = 10)
    {
        return $this->localeRepository->query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('code', 'like', "%$search%")
                        ->orWhere('is_default', 'like', "%$search%");
                });
            })
            ->orderBy($filters['sort_by'] ?? 'is_default', $filters['sort_direction'] ?? 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Store data to database
     * @param array $data
     * @return mixed
     */
    public function createLocale(array $data)
    {
        try {
            DB::beginTransaction();
            $result = $this->localeRepository->create($data);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create locale: ' . $e->getMessage());

            throw new \RuntimeException('Could not create locale');
        }
    }

    /**
     * Update existing record
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateLocale($id, array $data)
    {
        try {
            DB::beginTransaction();
            $result = $this->localeRepository->update($id, $data);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update locale: ' . $e->getMessage());

            throw new \RuntimeException('Could not update locale');
        }
    }

    /**
     * Delete record
     * @param int $id
     * @return mixed
     */
    public function deleteLocale(int $id)
    {
        try {
            DB::beginTransaction();
            $result = $this->localeRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete locale: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete locale');
        }
    }
}
