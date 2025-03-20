<?php

namespace App\Services\Dashboard;

use App\Repositories\DoctorDetailRepository;
use App\Repositories\DoctorRepository;
use App\Services\AttachmentService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardDoctorService
{
    protected $doctorRepository;
    protected $doctorDetailRepository;
    protected $attachmentService;

    public function __construct(DoctorRepository $doctorRepository, DoctorDetailRepository $doctorDetailRepository, AttachmentService $attachmentService)
    {
        $this->doctorRepository = $doctorRepository;
        $this->doctorDetailRepository = $doctorDetailRepository;
        $this->attachmentService = $attachmentService;
    }

    /**
     * Get paginated data
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getPaginatedDoctors(array $filters, ?int $perPage = 10): mixed
    {
        $query = $this->doctorRepository->query()
            ->with('image','specialties')
            ->when($filters['search'] ?? null, fn($query, $search) => $query->where('full_name', 'like', "%$search%"))
            ->orderBy($filters['sort_by'] ?? 'sort_order', $filters['sort_direction'] ?? 'asc');

        return $perPage ? $query->paginate($perPage)->withQueryString() : $query->get();
    }

    /**
     * Create record
     * @param array $data
     * @return mixed
     */
    public function createDoctor(array $data): mixed
    {
        try {
            DB::beginTransaction();
            $model = $this->doctorRepository->create($data);
            $model->specialties()->sync($data['specialties']);
            $model->languages()->sync($data['languages']);

            $file = $data['image'] ?? null;
            unset($data['image']);

            if ($file) {
                $this->attachmentService->attachFile($model, $file, 'image');
            }

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
    public function updateDoctor($id, mixed $data): mixed
    {
        try {
            DB::beginTransaction();
            $doctor = $this->doctorRepository->find($id);
            $doctor->fill($data);
            $doctor->save();

            $doctor->specialties()->sync($data['specialties']);
            $doctor->languages()->sync($data['languages']);

            $image = $data['image'] ?? null;
            unset($data['image']);

            if($image instanceof UploadedFile){
                if($doctor->image){
                    $this->attachmentService->deleteAttachment($doctor->image);
                }
                $this->attachmentService->attachFile($doctor, $image, 'image');
            }

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
    public function deleteDoctor($id): mixed
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
     * Update the order of doctor.
     *
     * @param array $data
     * @return bool
     * @throws \RuntimeException
     */
    public function updateDoctorOrder(array $data): bool
    {
        try {
            DB::beginTransaction();

            foreach ($data['orderedIds'] as $doctor) {
                $model = $this->doctorRepository->find($doctor['id']);

                if (!$model) {
                    throw new \RuntimeException("Doctor with ID {$doctor['id']} not found.");
                }

                $model->update(['sort_order' => $doctor['order']]);
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
     * Create doctor detial record
     * @param array $data
     * @return mixed
     */
    public function createDoctorDetail(array $data): mixed
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
    public function updateDoctorDetail(array $data, int $id): mixed
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
    public function deleteDoctorDetail(int $id): mixed
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
