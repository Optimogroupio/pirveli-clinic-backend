<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Page\DashboardStorePageRequest;
use App\Http\Requests\Page\DashboardStoreSpecialtyRequest;
use App\Http\Requests\Page\DashboardUpdatePageRequest;
use App\Http\Requests\Page\DashboardUpdateSpecialtyRequest;
use App\Repositories\PageRepository;
use App\Services\Dashboard\DashboardPageService;
use Illuminate\Support\Facades\Request;

class DashboardPageController extends Controller
{
    protected $pageRepository;
    protected $pageService;

    public function __construct(PageRepository $pageRepository, DashboardPageService $pageService)
    {
        $this->pageRepository = $pageRepository;
        $this->pageService = $pageService;
    }

    /**
     * List page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $pages = $this->pageService->getPaginatedPages($filters, 10);

        return inertia('Page/List', [
            'filters' => $filters,
            'pages' => $pages
        ]);
    }

    /**
     * Create page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        $pages = $this->pageRepository->all();
        return inertia('Page/Create', compact('pages'));
    }

    /**
     * Store method
     * @param DashboardStorePageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStorePageRequest $request)
    {
        $this->pageService->createPage($request->validated());

        Toast::message('Page created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.pages.index');
    }

    /**
     * Edit page
     * @param $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit($id)
    {
        $page = $this->pageRepository->find($id, ['translations']);

        return inertia('Page/Edit', [
            'page' => $page,
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdatePageRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdatePageRequest $request, $id)
    {

        $this->pageService->updateService($id, $request->validated());

        Toast::message('Page updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.pages.index');
    }

    /**
     * Delete method
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->pageService->deletePage($id);

        Toast::message('Page was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.pages.index');
    }
}
