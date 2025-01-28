<?php

namespace App\Interfaces;

interface SliderInterface
{
    public function all();
    public function find(int $id, array|null $with = null);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
