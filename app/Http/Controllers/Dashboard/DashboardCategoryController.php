<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\DashboardStoreCategoryRequest;
use App\Http\Requests\Category\DashboardUpdateCategoryRequest;
use App\Repositories\CategoryRepository;
use App\Repositories\ServiceRepository;
use App\Services\Dashboard\DashboardCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class DashboardCategoryController extends Controller
{
    protected CategoryRepository $categoryRepository;
    protected DashboardCategoryService $categoryService;
    protected ServiceRepository $serviceRepository;

    public function __construct(CategoryRepository $categoryRepository, DashboardCategoryService $categoryService, ServiceRepository $serviceRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryService = $categoryService;
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * List page
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $categories = $this->categoryService->getPaginatedCategories($filters, 10);
        return inertia('Category/List', [
            'filters' => $filters,
            'categories' => $categories
        ]);
    }

    /**
     * Create page
     * @return Response|ResponseFactory
     */
    public function create(): Response|ResponseFactory
    {
        $categories = $this->categoryRepository->all();
        $services = $this->serviceRepository->all();
        return inertia('Category/Create', compact('categories', 'services'));
    }

    /**
     * Store method
     * @param DashboardStoreCategoryRequest $request
     * @return RedirectResponse
     */
    public function store(DashboardStoreCategoryRequest $request): RedirectResponse
    {
        $this->categoryService->createCategory($request->validated());

        Toast::message('Category created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.categories.index');
    }

    /**
     * Edit page
     * @param $id
     * @return Response|ResponseFactory
     */
    public function edit($id): Response|ResponseFactory
    {
        $category = $this->categoryRepository->find($id, ['translations']);
        $services = $this->serviceRepository->all();

        return inertia('Category/Edit', [
            'category' => $category,
            'services' => $services
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdateCategoryRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(DashboardUpdateCategoryRequest $request, $id): RedirectResponse
    {

        $this->categoryService->updateCategory($id, $request->validated());

        Toast::message('Category updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.categories.index');
    }

    /**
     * Delete method
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $this->categoryService->deleteCategory($id);

        Toast::message('Category was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.categories.index');
    }
}
