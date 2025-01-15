<?php

namespace App\Interfaces;

interface LocaleInterface
{
    public function all();
    public function find(int $id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete(int $id);

    public function query();
}
