<?php


namespace App\Repositories;


class UserRepository extends ARepository
{

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->model->select(['id', 'name', 'email'])->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->model->where('id', $id)->firstOrFail();
    }
}