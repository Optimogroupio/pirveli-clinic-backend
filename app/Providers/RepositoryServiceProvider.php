<?php

namespace App\Providers;

use App\Interfaces\AdminInterface;
use App\Interfaces\PermissionInterface;
use App\Interfaces\RoleInterface;
use App\Repositories\AdminRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use App\Services\Admin\BackendAdminServiceInterface;
use App\Services\Dashboard\DashboardAdminService;
use App\Services\Dashboard\DashboardPermissionService;
use App\Services\Dashboard\DashboardRoleService;
use App\Services\Permission\BackendPermissionServiceInterface;
use App\Services\Role\BackendRoleServiceInterface;
use Illuminate\Support\ServiceProvider;

// Import the interfaces and implementations

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind Admin interfaces and services
        $this->app->bind(AdminInterface::class, AdminRepository::class);
        $this->app->bind(BackendAdminServiceInterface::class, DashboardAdminService::class);

        // Bind Role interfaces and services
        $this->app->bind(RoleInterface::class, RoleRepository::class);
        $this->app->bind(BackendRoleServiceInterface::class, DashboardRoleService::class);

        // Bind Permission interfaces and services
        $this->app->bind(PermissionInterface::class, PermissionRepository::class);
        $this->app->bind(BackendPermissionServiceInterface::class, DashboardPermissionService::class);
    }
}
