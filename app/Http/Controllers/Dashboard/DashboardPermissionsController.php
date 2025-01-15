<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\DashboardStorePermissionRequest;
use App\Http\Requests\Permission\DashboardUpdatePermissionRequest;
use App\Services\Dashboard\DashboardPermissionService;
use Illuminate\Support\Facades\Request;

class DashboardPermissionsController extends Controller
{
    protected $permissionService;

    public function __construct(DashboardPermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * List page
     * @param Request $request
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index(Request $request)
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $permissions = $this->permissionService->getPaginatedPermissions($filters, 10);

        return inertia('Permission/List', [
            'filters' => $filters,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Create page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        return inertia('Permission/Create');
    }

    /**
     * Store method
     * @param DashboardStorePermissionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStorePermissionRequest $request)
    {
        $this->permissionService->createPermission($request->validated());

        Toast::message('Permission created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();


        return redirect()->route('dashboard.permissions.index');
    }

    /**
     * Edit poge
     * @param int $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit(int $id)
    {
        $permission = $this->permissionService->getPermissionById($id);
        return inertia('Permission/Edit', compact('permission'));
    }

    /**
     * Update method
     * @param DashboardUpdatePermissionRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdatePermissionRequest $request, $id)
    {
        $this->permissionService->updatePermission($id, $request->validated());

        Toast::message('Permission updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return to_route('dashboard.permissions.index');
    }

    /**
     * Destroy method
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->permissionService->deletePermission($id);

        Toast::message('Permission was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return to_route('dashboard.permissions.index');
    }
}
