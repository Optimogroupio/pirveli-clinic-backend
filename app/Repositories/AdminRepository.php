<?php

namespace App\Repositories;

use App\Interfaces\AdminInterface;
use App\Models\DashboardUser;

class AdminRepository implements AdminInterface
{
    protected $model;

    public function __construct(DashboardUser $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
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
        $admin = $this->model->findOrFail($id);
        $admin->update($data);
        return $admin;
    }

    public function delete($id)
    {
        $admin = $this->model->findOrFail($id);
        return $admin->delete();
    }

    public function query()
    {
        return $this->model->newQuery();
    }
}
