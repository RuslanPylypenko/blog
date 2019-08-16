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
            ->select(['id', 'title', 'views', 'likes', 'created_at', 'updated_at', 'short_text', 'price'])
            ->with(['user'])
            ->where('status', Article::STATUS_ACTIVE)
            ->paginate($perPage);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model
            ->with(['user', 'comments', 'comments.user'])
            ->where(['id' => $id, 'status' => 1])
            ->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }


    public function getPrice($id)
    {
        return $this->model
            ->where(['id' => $id, 'status' => 1])
            ->select('price')
            ->first()->price;
    }


    /**
     * @param $id
     * @return Article
     */
    public function getWithoutFullText($id)
    {
        return $this->model
            ->where(['id' => $id, 'status' => 1])
            ->with(['user'])
            ->first();
    }
}