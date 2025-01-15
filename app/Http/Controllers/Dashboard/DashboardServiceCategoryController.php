<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceCategory\DashboardStoreServiceCategoryRequest;
use App\Http\Requests\ServiceCategory\DashboardUpdateServiceCategoryRequest;
use App\Repositories\ServiceCategoryRepository;
use App\Services\Dashboard\DashboardServiceCategoryService;
use Illuminate\Support\Facades\Request;

class DashboardServiceCategoryController extends Controller
{
    protected $serviceCategoryService;
    protected $serviceCategoryRepository;

    public function __construct(DashboardServiceCategoryService $serviceCategoryService, ServiceCategoryRepository $serviceCategoryRepository)
    {
        $this->serviceCategoryService = $serviceCategoryService;
        $this->serviceCategoryRepository = $serviceCategoryRepository;
    }

    /**
     * List page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $service_categories = $this->serviceCategoryService->getPaginatedPermissions($filters, 10);

        return inertia('ServiceCategory/List', [
            'filters' => $filters,
            'service_categories' => $service_categories
        ]);
    }

    /**
     * Create page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        return inertia('ServiceCategory/Create');
    }

    /**
     * Store method
     * @param DashboardStoreServiceCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStoreServiceCategoryRequest $request)
    {
        $this->serviceCategoryService->createServiceCategory($request->validated());

        Toast::message('Service category created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.service_categories.index');
    }

    /**
     * Edit page
     * @param $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit($id)
    {
        $service_category = $this->serviceCategoryRepository->find($id, ['translations']);

        return inertia('ServiceCategory/Edit', [
            'service_category' => $service_category,
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdateServiceCategoryRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdateServiceCategoryRequest $request, $id)
    {

        $this->serviceCategoryService->updateServiceCategory($id, $request->validated());

        Toast::message('Service category updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.service_categories.index');
    }

    /**
     * Delete method
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->serviceCategoryService->deleteServiceCategory($id);

        Toast::message('Service category was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.service_categories.index');
    }
}
