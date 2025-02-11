<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DashboardStoreDoctorRequest;
use App\Http\Requests\Doctor\DashboardUpdateDoctorRequest;
use App\Http\Requests\DoctorDetail\DashboardDeleteMultipleDoctorDetail;
use App\Http\Requests\DoctorDetail\DashboardStoreDoctorDetailRequest;
use App\Http\Requests\DoctorDetail\DashboardUpdateDoctorDetailRequest;
use App\Http\Requests\DoctorDetail\DashboardUpdateDoctorDetailOrder;
use App\Repositories\DoctorDetailRepository;
use App\Repositories\DoctorRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\SpecialtyRepository;
use App\Services\Dashboard\DashboardDoctorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;

class DashboardDoctorController extends Controller
{
    protected $doctorService;
    protected $doctorRepository;
    protected $serviceRepository;
    protected $specialtyRepository;
    protected $languageRepository;

    protected $doctorDetailRepository;

    public function __construct(DashboardDoctorService $doctorService, DoctorRepository $doctorRepository, ServiceRepository $serviceRepository, SpecialtyRepository $specialtyRepository, LanguageRepository $languageRepository, DoctorDetailRepository $doctorDetailRepository)
    {
        $this->doctorService = $doctorService;
        $this->doctorRepository = $doctorRepository;
        $this->serviceRepository = $serviceRepository;
        $this->specialtyRepository = $specialtyRepository;
        $this->languageRepository = $languageRepository;
        $this->doctorDetailRepository = $doctorDetailRepository;
    }

    /**
     * List page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $doctors = $this->doctorService->getPaginatedDoctors($filters, 10);

        return inertia('Doctor/List', [
            'filters' => $filters,
            'doctors' => $doctors
        ]);
    }

    /**
     * Create page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        $services = $this->serviceRepository->all();
        $specialties = $this->specialtyRepository->all();
        $languages = $this->languageRepository->all();

        return inertia('Doctor/Create', [
            'services' => $services,
            'specialties' => $specialties,
            'languages' => $languages
        ]);
    }

    /**
     * Store method
     * @param DashboardStoreDoctorRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStoreDoctorRequest $request)
    {
        $doctor = $this->doctorService->createDoctor($request->validated());

        Toast::message('Doctor created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.doctors.edit', $doctor);
    }

    /**
     * Edit page
     * @param $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit($id)
    {
        $doctor = $this->doctorRepository->find($id, ['translations', 'languages', 'image', 'educations', 'experiences', 'certificates', 'specialties']);
        $services = $this->serviceRepository->all();
        $specialties = $this->specialtyRepository->all();
        $languages = $this->languageRepository->all();

        return inertia('Doctor/Edit', [
            'doctor' => $doctor,
            'services' => $services,
            'specialties' => $specialties,
            'languages' => $languages
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdateDoctorRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdateDoctorRequest $request, $id)
    {
        $this->doctorService->updateDoctor($id, $request->validated());

        Toast::message('Doctor updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.doctors.index');
    }

    /**
     * Delete method
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->doctorService->deleteDoctor($id);

        Toast::message('Doctor was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.doctors.index');
    }

    /**
     * Create doctor detail page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function createDoctorDetail($doctorId, $type)
    {
        $doctor = $this->doctorRepository->find($doctorId, ['educations', 'educations.translations']);

        return inertia('Doctor/Detail/Create', [
            'type' => $type,
            'doctor' => $doctor,
            'doctorId' => $doctorId,
        ]);
    }

    /**
     * Store a new doctor detail.
     *
     * @param DashboardStoreDoctorDetailRequest $request
     * @param int $doctorId
     * @param string $type
     * @return RedirectResponse
     */
    public function storeDoctorDetail(DashboardStoreDoctorDetailRequest $request, $doctorId, $type)
    {
        if (!in_array($type, ['educations', 'experiences', 'certificates'])) {
            abort(404, 'Invalid detail type');
        }

        $this->doctorService->createDoctorDetail($request->validated());

        Toast::message('Doctor detail created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.doctors.edit', $doctorId);
    }

    /**
     * Edit page
     * @param $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function editDoctorDetail(int $doctorId, string $type, int $doctorDetailId)
    {
        $doctor = $this->doctorRepository->find($doctorId, ['educations', 'experiences', 'certificates']);
        $doctorDetail = $this->doctorDetailRepository->find($doctorDetailId, ['translations']);

        return inertia('Doctor/Detail/Edit', [
            'doctor' => $doctor,
            'type' => $type,
            'doctorDetail' => $doctorDetail
        ]);
    }

    /** Update doctor detail record
     * @param DashboardUpdateDoctorDetailRequest $request
     * @param $doctorId
     * @param $type
     * @param $id
     * @return RedirectResponse
     */
    public function updateDoctorDetail(DashboardUpdateDoctorDetailRequest $request, $doctorId, $type, $id)
    {
        if (!in_array($type, ['educations', 'experiences', 'certifications'])) {
            abort(404, 'Invalid detail type');
        }

        $this->doctorService->updateDoctorDetail($request->validated(), $id);

        Toast::message('Doctor detail updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.doctors.edit', $doctorId);
    }

    public function destroyDoctorDetail($doctorId, $type, $id)
    {
        if (!in_array($type, ['educations', 'experiences', 'certificates'])) {
            abort(404, 'Invalid detail type');
        }

        $this->doctorService->deleteDoctorDetail($id);

        Toast::message('Doctor detail deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.doctors.edit', $doctorId);
    }

    /**
     * Update doctor detail order
     * @param DashboardUpdateDoctorDetailOrder $request
     * @param $doctorId
     * @param $type
     * @return RedirectResponse
     */
    public function updateDoctorDetailOrder(DashboardUpdateDoctorDetailOrder $request, int $doctorId)
    {
        $this->doctorService->updateDoctorDetailOrder($request->validated());

        Toast::message('Doctor detail order updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.doctors.edit', $doctorId);
    }

    /**
     * Delete multiple doctor details
     * @param DashboardDeleteMultipleDoctorDetail $request
     * @param $doctorId
     * @param $type
     * @return RedirectResponse
     */
    public function deleteMultipleDoctorDetails(DashboardDeleteMultipleDoctorDetail $request, int $doctorId)
    {
        $this->doctorService->deleteMultipleDoctorDetails($request->validated());

        Toast::message('Doctor details was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.doctors.edit', $doctorId);
    }
}
