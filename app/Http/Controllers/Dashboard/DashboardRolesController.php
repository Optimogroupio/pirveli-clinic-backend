<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\Toast;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\DashboardStoreRoleRequest;
use App\Http\Requests\Role\DashboardUpdateRoleRequest;
use App\Interfaces\PermissionInterface;
use App\Interfaces\RoleInterface;
use App\Services\Dashboard\DashboardRoleService;
use Illuminate\Support\Facades\Request;

class DashboardRolesController extends Controller
{
    protected $roleService;
    protected $roleRepository;
    protected $permissionRepository;

    public function __construct(DashboardRoleService $roleService, RoleInterface $roleRepository, PermissionInterface $permissionRepository)
    {
        $this->roleService = $roleService;
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
        $roles = $this->roleService->getPaginatedPermissions($filters, 10);

        return inertia('Role/List', [
            'filters' => $filters,
            'roles' => $roles,
        ]);
    }

    /**
     * Create page
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        $permissions = $this->permissionRepository->all();
        $guards = $this->permissionRepository->getAuthGuards();

        return inertia('Role/Create', [
            'permissions' => $permissions,
            'guards' => $guards,
        ]);
    }

    /**
     * Store method
     * @param DashboardStoreRoleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DashboardStoreRoleRequest $request)
    {
        $this->roleService->createRole($request->validated());

        Toast::message('Role created successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.roles.index')->with('success', 'Role created successfully');
    }

    /**
     * Edit page
     * @param $id
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit($id)
    {
        $role = $this->roleRepository->find($id, ['permissions']);
        $permissions = $this->permissionRepository->all();
        $guards = $this->permissionRepository->getAuthGuards();

        return inertia('Role/Edit', [
            'role' => $role,
            'guards' => $guards,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update method
     * @param DashboardUpdateRoleRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(DashboardUpdateRoleRequest $request, $id)
    {
        $this->roleService->updateRole($id, $request->validated());

        Toast::message('Role updated successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.roles.index')->with('success', 'Role updated successfully');
    }

    /**
     * Delete method
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->roleService->deleteRole($id);

        Toast::message('Role was deleted successfully.')
            ->type('success')
            ->autoDismiss(5)
            ->position('bottom-right')
            ->send();

        return redirect()->route('dashboard.roles.index')->with('success', 'Role deleted successfully');
    }
}
