<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Locale\DashboardStoreLocaleRequest;
use App\Http\Requests\Locale\DashboardUpdateLocaleRequest;
use App\Repositories\LocaleRepository;
use App\Services\Dashboard\DashboardLocaleService;
use Illuminate\Support\Facades\Request;

class DashboardLocaleController extends Controller
{
    protected $localeService;
    protected $localeRepository;

    public function __construct(DashboardLocaleService $localeService, LocaleRepository $localeRepository)
    {
        $this->localeService = $localeService;
        $this->localeRepository = $localeRepository;
    }

    /**
     * List page
     * @param Request $request
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index(Request $request)
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $locales = $this->localeService->getPaginatedPermissions($filters, 10);

        return inertia('Locale/List', [
            'filters' => $filters,
            'locales' => $locales,
        ]);
    }

    /**
     * Create page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        return inertia('Locale/Create');
    }

    /**
     * Store method
     * @param DashboardStoreLocaleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStoreLocaleRequest $request)
    {
        $this->localeService->createLocale($request->validated());

        Toast::message('Locale created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();


        return redirect()->route('dashboard.locales.index');
    }

    /**
     * Edit poge
     * @param int $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit(int $id)
    {
        $locale = $this->localeRepository->find($id);
        return inertia('Locale/Edit', compact('locale'));
    }

    /**
     * Update method
     * @param DashboardUpdateLocaleRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdateLocaleRequest $request, $id)
    {
        $this->localeService->updateLocale($id, $request->validated());

        Toast::message('Locale updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return to_route('dashboard.locales.index');
    }

    /**
     * Destroy method
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->localeService->deleteLocale($id);

        Toast::message('Locale was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return to_route('dashboard.locales.index');
    }
}
