<?php


namespace App\Repositories;


use App\Entities\Article;

class ArticleRepository extends ARepository
{

    /**
     * @param int $perPage
     * @param string $sort
     * @param string $dir
     * @return mixed
     */
    public function get($perPage = 12, $sort = 'created_at', $dir = 'asc')
    {
        return $this->model->orderBy($sort, $dir)
            ->where('status', Article::STATUS_ACTIVE)
            ->paginate($perPage);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function findOne($id)
    {
        return $this->model->where('id', $id)->firstOrFail();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOneWithComments($id)
    {
        return $this->model->with(['comments'])->where('id', $id)->firstOrFail();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }
}