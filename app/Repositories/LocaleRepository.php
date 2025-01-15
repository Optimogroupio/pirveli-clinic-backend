<?php

namespace App\Repositories;

use App\Interfaces\LocaleInterface;
use App\Models\Locale;

class LocaleRepository implements LocaleInterface
{
    protected $model;

    public function __construct(Locale $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id, array|null $with = null)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $locale = $this->model->findOrFail($id);
        $locale->update($data);
        return $locale;
    }

    public function delete($id)
    {
        $locale = $this->model->findOrFail($id);
        return $locale->delete();
    }

    public function query()
    {
        return $this->model->newQuery();
    }
}
