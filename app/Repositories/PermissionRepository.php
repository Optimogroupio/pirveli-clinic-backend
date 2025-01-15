<?php

namespace App\Repositories;

use App\Interfaces\PermissionInterface;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionInterface
{
    protected $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function getByIds(array $ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $permission = $this->model->findOrFail($id);
        $permission->update($data);
        return $permission;
    }

    public function delete(int $id)
    {
        $permission = $this->model->findOrFail($id);
        return $permission->delete();
    }

    public function query()
    {
        return $this->model->newQuery();
    }

    public function getAuthGuards()
    {
        return array_map(fn($guard) => ['label' => $guard, 'value' => $guard], array_keys(config('auth.guards')));
    }
}
