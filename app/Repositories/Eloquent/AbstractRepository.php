<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\Paginator;

abstract class AbstractRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var
     */
    protected $wheres;

    /**
     * @var
     */
    protected $query;

    /**
     *
     */
    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    /**
     * @return Model
     */
    protected function resolveModel(): Model
    {
        return app($this->model);
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateById($id, $data): bool
    {

        return $this->model->find($id)->update($data);
    }

    /**
     * @param $id
     * @param  array|string $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {

        return $this->model->find($id, $columns);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create(array $data = [])
    {

        return $this->model->create($data);
    }

    /**
     * @return mixed
     */
    public function all()
    {

        return $this->model->all();
    }

    /**
     * @param  array  $columns
     * @return mixed
     */
    public function first(array $columns = ['*'])
    {
        $this->newQuery()->mountWhere();
        $model = $this->query->first($columns);

        return $model;
    }

    /**
     * @param  array  $columns
     * @return mixed
     */
    public function get(array $columns = ['*'])
    {
        $this->newQuery()->mountWhere();
        $models = $this->query->get($columns);

        return $models;
    }

    public function where($column, $value, $operator = '=')
    {
        return $this->model->where($column, $value, $operator);
    }

    public function paginate(int $limit): Paginator
    {
        return $this->model->paginate($limit);
    }
}
