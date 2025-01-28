<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Slider\DashboardStoreSliderRequest;
use App\Http\Requests\Slider\DashboardUpdateSliderOrder;
use App\Http\Requests\Slider\DashboardUpdateSliderRequest;
use App\Repositories\SliderRepository;
use App\Services\Dashboard\DashboardSliderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;

class DashboardSliderController extends Controller
{
    protected $sliderRepository;
    protected $sliderService;

    /**
     * @param $sliderRepository
     * @param $sliderService
     */
    public function __construct(SliderRepository $sliderRepository, DashboardSliderService $sliderService,)
    {
        $this->sliderRepository = $sliderRepository;
        $this->sliderService = $sliderService;
    }


    /**
     * List page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $slider = $this->sliderService->getPaginatedSlider($filters, 10);

        return inertia('Slider/List', [
            'filters' => $filters,
            'slider' => $slider
        ]);
    }

    /**
     * Create page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        $slider = $this->sliderRepository->all();
        return inertia('Slider/Create', compact('slider'));
    }

    /**
     * Store method
     * @param DashboardStoreSliderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStoreSliderRequest $request)
    {
        $this->sliderService->createSlider($request->validated());

        Toast::message('Slider created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.slider.index');
    }

    /**
     * Edit page
     * @param $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit($id)
    {
        $slider = $this->sliderRepository->find($id, ['translations', 'image']);

        return inertia('Slider/Edit', [
            'slider' => $slider,
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdateSliderRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdateSliderRequest $request, $id)
    {
        $this->sliderService->updateSlider($id, $request->validated());

        Toast::message('Slider updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.slider.index');
    }

    /**
     * Delete method
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->sliderRepository->delete($id);

        Toast::message('Slider was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.slider.index');
    }

    /**
     * Update slider order
     * @param DashboardUpdateSliderOrder $request
     * @return RedirectResponse
     */
    public function updateOrder(DashboardUpdateSliderOrder $request)
    {
        $this->sliderService->updateOrder($request->validated());

        Toast::message('Slider order updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.slider.index');
    }
}
