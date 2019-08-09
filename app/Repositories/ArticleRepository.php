<?php


namespace App\Repositories;


use App\Entities\Article;

class ArticleRepository extends ARepository
{
    /**
     * @param $perPage
     * @return mixed
     */
    public function get($perPage)
    {
        return $this->model->orderBy('created_at', 'desc')
            ->where('status', Article::STATUS_ACTIVE)
            ->paginate($perPage);
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