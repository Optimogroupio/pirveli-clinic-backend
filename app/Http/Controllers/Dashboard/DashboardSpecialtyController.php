<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Specialty\DashboardStoreSpecialtyRequest;
use App\Http\Requests\Specialty\DashboardUpdateSpecialtyRequest;
use App\Repositories\SpecialtyRepository;
use App\Services\Dashboard\DashboardSpecialtyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class DashboardSpecialtyController extends Controller
{
    protected $specialtyService;
    protected $specialtyRepository;

    public function __construct(DashboardSpecialtyService $specialtyService, SpecialtyRepository $specialtyRepository)
    {
        $this->specialtyService = $specialtyService;
        $this->specialtyRepository = $specialtyRepository;
    }

    /**
     * List page
     * @return Response|ResponseFactory
     */
    public function index()
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $specialties = $this->specialtyService->getPaginatedSpecialties($filters, 10);

        return inertia('Specialty/List', [
            'filters' => $filters,
            'specialties' => $specialties
        ]);
    }

    /**
     * Create page
     * @return Response|ResponseFactory
     */
    public function create()
    {
        return inertia('Specialty/Create');
    }

    /**
     * Store method
     * @param DashboardStoreSpecialtyRequest $request
     * @return RedirectResponse
     */
    public function store(DashboardStoreSpecialtyRequest $request)
    {
        $this->specialtyService->createSpecialty($request->validated());

        Toast::message('Specialty created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.specialties.index');
    }

    /**
     * Edit page
     * @param $id
     * @return Response|ResponseFactory
     */
    public function edit($id)
    {
        $specialty = $this->specialtyRepository->find($id, ['translations']);

        return inertia('Specialty/Edit', [
            'specialty' => $specialty,
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdateSpecialtyRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(DashboardUpdateSpecialtyRequest $request, $id)
    {

        $this->specialtyService->updateSpecialty($id, $request->validated());

        Toast::message('Specialty updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.specialties.index');
    }

    /**
     * Delete method
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $this->specialtyService->deleteSpecialty($id);

        Toast::message('Specialty was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.specialties.index');
    }
}
