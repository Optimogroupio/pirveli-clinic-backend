<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DashboardStoreAdminRequest;
use App\Http\Requests\Admin\DashboardUpdateAdminRequest;
use App\Interfaces\AdminInterface;
use App\Interfaces\PermissionInterface;
use App\Interfaces\RoleInterface;
use App\Services\Dashboard\DashboardAdminService;
use Illuminate\Support\Facades\Request;

class DashboardAdminsController extends Controller
{
    protected $adminService;
    protected $adminRepository;
    protected $roleRepository;
    protected $permissionRepository;

    public function __construct(DashboardAdminService $adminService, AdminInterface $adminRepository, RoleInterface $roleRepository, PermissionInterface $permissionRepository)
    {
        $this->adminService = $adminService;
        $this->adminRepository = $adminRepository;
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * List page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        $filters = Request::only('search', 'sort_by', 'sort_direction');
        $administrators = $this->adminService->getPaginatedPermissions($filters, 10);

        return inertia('Admin/List', [
            'filters' => $filters,
            'administrators' => $administrators
        ]);
    }

    /**
     * Create page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        $roles = $this->roleRepository->all();
        $permissions = $this->permissionRepository->all();

        return inertia('Admin/Create',[
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    /**
     * Store method
     * @param DashboardStoreAdminRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStoreAdminRequest $request)
    {
        $this->adminService->createAdmin($request->validated());

        Toast::message('Administrator created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.administrators.index');
    }

    /**
     * Edit page
     * @param $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit($id)
    {
        $admin = $this->adminRepository->find($id, ['roles','permissions']);
        $roles = $this->roleRepository->all();
        $permissions = $this->permissionRepository->all();

        return inertia('Admin/Edit', [
            'admin' => $admin,
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdateAdminRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdateAdminRequest $request, $id)
    {
        $this->adminService->updateAdmin($id, $request->validated());

        Toast::message('Administrator updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.administrators.index');
    }

    /**
     * Delete method
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->adminService->deleteAdmin($id);

        Toast::message('Administrator was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.administrators.index');
    }
}
