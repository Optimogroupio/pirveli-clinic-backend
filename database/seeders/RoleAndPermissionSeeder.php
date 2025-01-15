<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'permission-list',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'permission-create',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'permission-update',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'permission-delete',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'role-list',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'role-create',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'role-update',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'role-delete',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'admin-list',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'admin-create',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'admin-update',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'admin-delete',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'news-list',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'news-create',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'news-update',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'news-delete',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'doctor-list',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'doctor-create',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'doctor-update',
                'guard_name' => 'dashboard',
            ],
            [
                'name' => 'doctor-delete',
                'guard_name' => 'dashboard',
            ],
        ];

        $roles = [
            [
                'name' => 'Admin',
                'guard_name' => 'dashboard',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        foreach ($roles as $role) {
            $role = Role::create($role);

            $matchingPermissions = collect($permissions)->filter(function ($permission) use ($role) {
                return $permission['guard_name'] === $role->guard_name;
            })->pluck('name')->toArray();

            $role->givePermissionTo($matchingPermissions);
        }
    }
}
