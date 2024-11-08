<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * @param  array  $attributes
     * @param  array  $options
     * @return mixed
     */
    public function update(array $attributes = [], array $options = [])
    {
        $this->newQuery()->mountWhere();
        $models = $this->query->update($attributes, $options);

        return $models;
    }

    public function firstOrNew(array $attributes, array $values = [])
    {
        return $this->model->firstOrNew($attributes, $values);
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

}
