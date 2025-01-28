<?php

namespace App\Repositories;

use App\Interfaces\SettingsInterface;
use App\Models\Settings;

class SettingsRepository implements SettingsInterface
{

    protected $model;

    public function __construct(Settings $model)
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
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        return $model->delete();
    }

    public function query()
    {
        return $this->model->newQuery();
    }
}
