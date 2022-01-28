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
        return $this->model->query()->findOrFail($id);
    }

    public function delete($id)
    {
        $model = $this->model->query()->findOrFail($id);
        $model->delete();
    }
}
