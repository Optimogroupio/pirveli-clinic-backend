<?php

namespace App\Services\Dashboard;

use App\Repositories\DoctorDetailRepository;
use App\Repositories\DoctorRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardDoctorService
{
    protected $doctorRepository;
    protected $doctorDetailRepository;

    public function __construct(DoctorRepository $doctorRepository, DoctorDetailRepository $doctorDetailRepository)
    {
        $this->doctorRepository = $doctorRepository;
        $this->doctorDetailRepository = $doctorDetailRepository;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedDoctors(array $filters, int $perPage = 10)
    {
        return $this->doctorRepository->query()
            ->when($filters['search'] ?? null, fn($query, $search) => $query->where('full_name', 'like', "%$search%"))
            ->orderBy($filters['sort_by'] ?? 'id', $filters['sort_direction'] ?? 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Create record
     * @param array $data
     * @return mixed
     */
    public function createDoctor(array $data)
    {
        try {
            DB::beginTransaction();
            $model = $this->doctorRepository->create($data);
            $model->specialties()->sync($data['specialties']);
            $model->languages()->sync($data['languages']);
            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create doctor: ' . $e->getMessage());

            throw new \RuntimeException('Could not create doctor');
        }
    }

    /**
     * Update record
     * @param $id
     * @param mixed $data
     * @return mixed
     */
    public function updateDoctor($id, mixed $data)
    {
        try {
            DB::beginTransaction();
            $doctor = $this->doctorRepository->find($id);
            $doctor->fill($data);
            $doctor->save();

            $doctor->specialties()->sync($data['specialties']);
            $doctor->languages()->sync($data['languages']);

            DB::commit();

            return $doctor;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update doctor: ' . $e->getMessage());

            throw new \RuntimeException('Could not update doctor', $e);
        }
    }

    /**
     * Delete record
     * @param $id
     * @return mixed
     */
    public function deleteDoctor($id)
    {
        try {
            DB::beginTransaction();
            $result = $this->doctorRepository->delete($id);
            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete doctor: ' . $e->getMessage());

            throw new \RuntimeException('Could not delete doctor');
        }
    }

    /**
     * Create doctor detial record
     * @param array $data
     * @return mixed
     */
    public function createDoctorDetail(array $data)
    {
        try {
            $model = $this->doctorDetailRepository->create($data);
            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create doctor detail: ' . $e->getMessage());

            throw new \RuntimeException('Could not create doctor detail');
        }
    }

    /**
     * Update doctor detial record
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function updateDoctorDetail(array $data, int $id)
    {
        try {
            DB::beginTransaction();
            $model = $this->doctorDetailRepository->find($id);
            $model->fill($data);
            $model->save();
            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create doctor detail: ' . $e->getMessage());

            throw new \RuntimeException('Could not create doctor detail');
        }
    }

    /**
     * Delete doctor detial record
     * @param int $id
     * @return mixed
     */
    public function deleteDoctorDetail(int $id)
    {
        try {
            DB::beginTransaction();
            $result = $this->doctorDetailRepository->delete($id);
            DB::commit();

            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete doctor detail: ' . $e->getMessage());
            throw new \RuntimeException('Could not delete doctor detail');
        }
    }

    /**
     * Update the order of doctor details.
     *
     * @param array $data
     * @return bool
     * @throws \RuntimeException
     */
    public function updateDoctorDetailOrder(array $data): bool
    {
        try {
            DB::beginTransaction();

            foreach ($data['orderedIds'] as $detail) {
                $model = $this->doctorDetailRepository->find($detail['id']);

                if (!$model) {
                    throw new \RuntimeException("DoctorDetail with ID {$detail['id']} not found.");
                }

                $model->update(['sort_order' => $detail['order']]);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update doctor detail order: ' . $e->getMessage());
            throw new \RuntimeException('Could not update doctor detail order');
        }
    }

    /**
     * Delete multiple order details.
     *
     * @param array $data
     * @return bool
     * @throws \RuntimeException
     */
    public function deleteMultipleDoctorDetails(array $data): bool
    {
        try {
            DB::beginTransaction();

            foreach ($data['ids'] as $detail) {
                $this->deleteDoctorDetail($detail);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete multiple doctor details: ' . $e->getMessage());
            throw new \RuntimeException('Could not delete multiple doctor details');
        }
    }
}
