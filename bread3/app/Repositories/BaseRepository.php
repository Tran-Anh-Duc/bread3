<?php

namespace App\Repositories;

class BaseRepository
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getById($id)
    {
        return $this->model->query()->find($id);
    }

    public function delete($id)
    {
        $result = $this->model->find($id);
        return $result->delete();
    }
}
