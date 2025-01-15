<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Language\DashboardStoreLanguageRequest;
use App\Http\Requests\Language\DashboardUpdateLanguageRequest;
use App\Repositories\LanguageRepository;
use App\Services\Dashboard\DashboardLanguageService;
use Illuminate\Support\Facades\Request;

class DashboardLanguageController extends Controller
{
    protected $languageService;
    protected $languageRepository;

    public function __construct(DashboardLanguageService $languageService, LanguageRepository $languageRepository)
    {
        $this->languageService = $languageService;
        $this->languageRepository = $languageRepository;
    }

    /**
     * List page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $languages = $this->languageService->getPaginatedLanguages($filters, 10);

        return inertia('Language/List', [
            'filters' => $filters,
            'languages' => $languages
        ]);
    }

    /**
     * Create page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        return inertia('Language/Create');
    }

    /**
     * Store method
     * @param DashboardStoreLanguageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStoreLanguageRequest $request)
    {
        $this->languageService->createLanguage($request->validated());

        Toast::message('Language created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.languages.index');
    }

    /**
     * Edit page
     * @param $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit($id)
    {
        $language = $this->languageRepository->find($id, ['translations']);

        return inertia('Language/Edit', [
            'language' => $language,
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdateLanguageRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdateLanguageRequest $request, $id)
    {

        $this->languageService->updateLanguage($id, $request->validated());

        Toast::message('Language updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.languages.index');
    }

    /**
     * Delete method
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->languageService->deleteLanguage($id);

        Toast::message('Language was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.languages.index');
    }
}
