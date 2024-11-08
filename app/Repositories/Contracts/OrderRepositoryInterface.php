<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface
{
    public function create(array $data = []);
    public function find($id, $columns = ['*']);
    public function updateById($id, $data);
}
