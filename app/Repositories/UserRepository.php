<?php


namespace App\Repositories;


class UserRepository extends ARepository
{

    public function find($id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function findOrFail($id)
    {
        return $this->model->where('id', $id)->firstOrFail();
    }
}