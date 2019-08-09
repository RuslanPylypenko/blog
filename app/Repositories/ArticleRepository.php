<?php


namespace App\Repositories;


class ArticleRepository extends ARepository
{
    /**
     * @param $perPage
     * @return mixed
     */
    public function get($perPage)
    {
        return $this->model->paginate($perPage);
    }


    public function findOne($id)
    {
        return $this->model->where('id', $id)->first();
    }
}