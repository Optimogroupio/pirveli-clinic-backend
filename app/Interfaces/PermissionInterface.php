<?php

namespace App\Interfaces;

interface PermissionInterface
{
    public function all();
    public function getByIds(array $ids);
    public function find(int $id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete(int $id);
    public function query();
    public function getAuthGuards();
}
