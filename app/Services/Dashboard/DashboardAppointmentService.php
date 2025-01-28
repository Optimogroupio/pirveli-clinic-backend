<?php

namespace App\Services\Dashboard;

use App\Repositories\AppointmnetRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardAppointmentService
{
    protected $appointmnetRepository;

    public function __construct(AppointmnetRepository $appointmnetRepository)
    {
        $this->appointmnetRepository = $appointmnetRepository;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedAppointments(array $filters, int $perPage = 10): mixed
    {
        return $this->appointmnetRepository->query()
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
    public function createAppointment(array $data): mixed
    {
        try {
            DB::beginTransaction();
            $model = $this->appointmnetRepository->create($data);

            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create appointment: ' . $e->getMessage());

            throw new \RuntimeException('Could not create appointment');
        }
    }

    /**
     * Update record
     * @param $id
     * @param mixed $data
     * @return mixed
     */
    public function updateAppointment($id, mixed $data): mixed
    {
        try {
            DB::beginTransaction();
            $news = $this->appointmnetRepository->find($id);
            $news->fill($data);
            $news->save();

            DB::commit();

            return $news;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update appointment: ' . $e->getMessage());

            throw new \RuntimeException('Could not update appointment', $e);
        }
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deleteAppointment($id): mixed
    {
        try {
            DB::beginTransaction();
            $result = $this->appointmnetRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete appointment: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete appointment');
        }
    }
}
