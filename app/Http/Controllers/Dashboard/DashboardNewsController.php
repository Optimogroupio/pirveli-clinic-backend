<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\News\DashboardStoreNewsRequest;
use App\Http\Requests\News\DashboardUpdateNewsRequest;
use App\Repositories\DoctorRepository;
use App\Repositories\NewsRepository;
use App\Repositories\ServiceRepository;
use App\Services\Dashboard\DashboardNewsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class DashboardNewsController extends Controller
{
    protected NewsRepository $newsRepository;
    protected DashboardNewsService $newsService;
    protected ServiceRepository $serviceRepository;

    protected DoctorRepository $doctorRepository;

    /**
     * @param NewsRepository $newsRepository
     * @param DashboardNewsService $newsService
     * @param ServiceRepository $serviceRepository
     * @param DoctorRepository $doctorRepository
     */
    public function __construct(NewsRepository $newsRepository, DashboardNewsService $newsService, ServiceRepository $serviceRepository, DoctorRepository $doctorRepository)
    {
        $this->newsRepository = $newsRepository;
        $this->newsService = $newsService;
        $this->serviceRepository = $serviceRepository;
        $this->doctorRepository = $doctorRepository;
    }


    /**
     * List page
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $news = $this->newsService->getPaginatedNews($filters, 10);

        return inertia('News/List', [
            'filters' => $filters,
            'news' => $news
        ]);
    }

    /**
     * Create page
     * @return Response|ResponseFactory
     */
    public function create(): Response|ResponseFactory
    {
        $news = $this->newsRepository->all();
        $services = $this->serviceRepository->all();
        $doctors = $this->doctorRepository->all();
        return inertia('News/Create', compact('news','services', 'doctors'));
    }

    /**
     * Store method
     * @param DashboardStoreNewsRequest $request
     * @return RedirectResponse
     */
    public function store(DashboardStoreNewsRequest $request): RedirectResponse
    {
        $this->newsService->createNews($request->validated());

        Toast::message('News created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.news.index');
    }

    /**
     * Edit page
     * @param $id
     * @return Response|ResponseFactory
     */
    public function edit($id): Response|ResponseFactory
    {
        $news = $this->newsRepository->find($id, ['doctors','translations', 'image']);
        $services = $this->serviceRepository->all();
        $doctors = $this->doctorRepository->all();

        return inertia('News/Edit', [
            'news' => $news,
            'services' => $services,
            'doctors' => $doctors
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdateNewsRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(DashboardUpdateNewsRequest $request, $id): RedirectResponse
    {
        $this->newsService->updateNews($id, $request->validated());

        Toast::message('News updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.news.index');
    }

    /**
     * Delete method
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $this->newsRepository->delete($id);

        Toast::message('News was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.news.index');
    }
}
