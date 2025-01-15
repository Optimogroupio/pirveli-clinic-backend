<?php

namespace App\Repositories;

use App\Interfaces\RoleInterface;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleInterface
{
    protected $model;

    public function __construct(Role $model)
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

    public function find($id, array|null $with = null)
    {
        $query = $this->model->newQuery();

        if (!is_null($with)) {
            $query->with($with);
        }

        return $query->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $role = $this->model->findOrFail($id);
        $role->update($data);
        return $role;
    }

    public function delete($id)
    {
        $role = $this->model->findOrFail($id);
        return $role->delete();
    }

    public function query()
    {
        return $this->model->newQuery();
    }
}
