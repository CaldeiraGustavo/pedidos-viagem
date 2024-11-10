<?php

namespace App\Repositories\Contracts;
use Illuminate\Contracts\Pagination\Paginator;

interface OrderRepositoryInterface
{
    public function create(array $data = []);
    public function find($id, $columns = ['*']);
    public function updateById($id, $data);
    public function where($column, $value, $operator = '=');
    public function paginate(int $limit): Paginator;
}
