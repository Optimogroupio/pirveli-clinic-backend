<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\DashboardStoreAppointmentRequest;
use App\Http\Requests\Appointment\DashboardUpdateAppointmentRequest;
use App\Repositories\AppointmnetRepository;
use App\Repositories\DoctorRepository;
use App\Repositories\SpecialtyRepository;
use App\Services\Dashboard\DashboardAppointmentService;
use Illuminate\Support\Facades\Request;

class DashboardAppointmentController extends Controller
{
    protected $appointmentRepository;
    protected $appointmentService;
    protected $specialtyRepository;
    protected $doctorRepository;
    public function __construct(AppointmnetRepository $appointmentRepository, DashboardAppointmentService $appointmentService, SpecialtyRepository $specialtyRepository, DoctorRepository $doctorRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
        $this->appointmentService = $appointmentService;
        $this->specialtyRepository = $specialtyRepository;
        $this->doctorRepository = $doctorRepository;
    }


    /**
     * List page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $appointments = $this->appointmentService->getPaginatedAppointments($filters, 10);

        return inertia('Appointment/List', [
            'filters' => $filters,
            'appointments' => $appointments
        ]);
    }

    /**
     * Create page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        $appointments = $this->appointmentRepository->all();
        $specialties = $this->specialtyRepository->all();
        $doctors = $this->doctorRepository->all();
        return inertia('Appointment/Create', compact('appointments','specialties', 'doctors'));
    }

    /**
     * Store method
     * @param DashboardStoreAppointmentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStoreAppointmentRequest $request)
    {
        $this->appointmentService->createAppointment($request->validated());

        Toast::message('Appointment created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.appointments.index');
    }

    /**
     * Edit page
     * @param $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit($id)
    {
        $appointment = $this->appointmentRepository->find($id);
        $specialties = $this->specialtyRepository->all();
        $doctors = $this->doctorRepository->all();

        return inertia('Appointment/Edit', [
            'appointment' => $appointment,
            'specialties' => $specialties,
            'doctors' => $doctors
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdateAppointmentRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdateAppointmentRequest $request, $id)
    {
        $this->appointmentService->updateAppointment($id, $request->validated());

        Toast::message('Appointment updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.appointments.index');
    }

    /**
     * Delete method
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->appointmentRepository->delete($id);

        Toast::message('Appointment was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.appointments.index');
    }
}
