<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DashboardStoreSettingsRequest;
use App\Http\Requests\Settings\DashboardUpdateSettingsRequest;
use App\Repositories\SettingsRepository;
use App\Services\Dashboard\DashboardSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;

class DashboardSettingsController extends Controller
{
    protected $settingsRepository;
    protected $settingsService;

    /**
     * @param $settingsRepository
     * @param $settingsService
     */
    public function __construct(SettingsRepository $settingsRepository, DashboardSettingsService $settingsService)
    {
        $this->settingsRepository = $settingsRepository;
        $this->settingsService = $settingsService;
    }

    /**
     * List page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $settings = $this->settingsService->getPaginatedSettings($filters, 10);

        return inertia('Settings/List', [
            'filters' => $filters,
            'settings' => $settings
        ]);
    }

    /**
     * Create page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        $settings = $this->settingsRepository->all();
        return inertia('Settings/Create', compact('settings'));
    }

    /**
     * Store method
     * @param DashboardStoreSettingsRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStoreSettingsRequest $request)
    {
        $this->settingsService->createSettings($request->validated());

        Toast::message('Settings created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.settings.index');
    }

    /**
     * Edit page
     * @param $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit($id)
    {
        $settings = $this->settingsRepository->find($id, ['translations', 'banner_image', 'logo']);

        return inertia('Settings/Edit', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdateSettingsRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdateSettingsRequest $request, $id)
    {
        $this->settingsService->updateSettings($id, $request->validated());

        Toast::message('Settings updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.settings.index');
    }

    /**
     * Delete method
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->settingsService->deleteSettings($id);

        Toast::message('Settings was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.settings.index');
    }
}
