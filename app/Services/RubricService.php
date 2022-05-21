<?php

namespace App\Services;

use App\Models\Rubric;

class RubricService
{
    public function __construct(protected Rubric $model) {} 
    
    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($attributes)
    {
        return $this->model->create($attributes);
    }

    public function index()
    {
        return $this->model->with(
                [
                    'childrenWith', 'news' 
                ]
            )
            ->whereNull('parent_id')
            ->get();
    }
}