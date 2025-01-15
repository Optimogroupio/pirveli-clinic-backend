<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\News\DashboardStoreAppointmentRequest;
use App\Http\Requests\News\DashboardUpdateAppointmentRequest;
use App\Repositories\DoctorRepository;
use App\Repositories\NewsRepository;
use App\Repositories\ServiceRepository;
use App\Services\Dashboard\DashboardNewsService;
use Illuminate\Support\Facades\Request;

class DashboardNewsController extends Controller
{
    protected $newsRepository;
    protected $newsService;
    protected $serviceRepository;

    protected $doctorRepository;

    /**
     * @param $newsRepository
     * @param $newsService
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
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
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
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        $news = $this->newsRepository->all();
        $services = $this->serviceRepository->all();
        $doctors = $this->doctorRepository->all();
        return inertia('News/Create', compact('news','services', 'doctors'));
    }

    /**
     * Store method
     * @param DashboardStoreAppointmentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStoreAppointmentRequest $request)
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
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit($id)
    {
        $news = $this->newsRepository->find($id, ['translations', 'image']);
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
     * @param DashboardUpdateAppointmentRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdateAppointmentRequest $request, $id)
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
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
