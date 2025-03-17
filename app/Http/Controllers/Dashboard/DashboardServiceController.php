<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorDetail\DashboardUpdateDoctorDetailOrder;
use App\Http\Requests\Service\DashboardStoreLanguageRequest;
use App\Http\Requests\Service\DashboardStoreServiceRequest;
use App\Http\Requests\Service\DashboardUpdateLanguageRequest;
use App\Http\Requests\Service\DashboardUpdateServiceOrderRequest;
use App\Http\Requests\Service\DashboardUpdateServiceRequest;
use App\Repositories\ServiceCategoryRepository;
use App\Repositories\ServiceRepository;
use App\Services\Dashboard\DashboardServiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;

class DashboardServiceController extends Controller
{
    protected $serviceService;
    protected $serviceRepository;
    protected $serviceCategoryRepository;

    public function __construct(DashboardServiceService $serviceService, ServiceRepository $serviceRepository, ServiceCategoryRepository $serviceCategoryRepository)
    {
        $this->serviceService = $serviceService;
        $this->serviceRepository = $serviceRepository;
        $this->serviceCategoryRepository = $serviceCategoryRepository;
    }

    /**
     * List page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction', 'per_page');
        $perPage = $filters['per_page'] ?? 10;

        $perPage = ($perPage === 'all') ? 100 : (int) $perPage;
        $services = $this->serviceService->getPaginatedServices($filters, $perPage);

        return inertia('Service/List', [
            'filters' => $filters,
            'services' => $services
        ]);
    }

    /**
     * Create page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        $service_categories = $this->serviceCategoryRepository->all();
        return inertia('Service/Create', compact('service_categories'));
    }

    /**
     * Store method
     * @param DashboardStoreServiceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStoreServiceRequest $request)
    {
        $this->serviceService->createService($request->validated());

        Toast::message('Service created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.services.index');
    }

    /**
     * Edit page
     * @param $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit($id)
    {
        $service = $this->serviceRepository->find($id, ['image', 'translations']);
        $service_categories = $this->serviceCategoryRepository->all();

        return inertia('Service/Edit', [
            'service' => $service,
            'service_categories' => $service_categories
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdateServiceRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdateServiceRequest $request, $id)
    {

        $this->serviceService->updateService($id, $request->validated());

        Toast::message('Service updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.services.index');
    }

    /**
     * Delete method
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->serviceService->deleteService($id);

        Toast::message('Service was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.services.index');
    }

    /**
     * Update service order
     * @param DashboardUpdateServiceOrderRequest $request
     * @return RedirectResponse
     */
    public function updateServiceOrder(DashboardUpdateServiceOrderRequest $request)
    {
        $this->serviceService->updateServiceOrder($request->validated());

        Toast::message('Service order updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.services.index');
    }
}
