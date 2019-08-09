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
        return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
    }


    public function findOne($id)
    {
        return $this->model->where('id', $id)->firstOrFail();
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}