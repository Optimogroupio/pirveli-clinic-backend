<?php

namespace App\Interfaces;

interface RoleInterface
{
    public function all();
    public function getByIds(array $ids);
    public function find(int $id, array|null $with = null);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function query();
}
