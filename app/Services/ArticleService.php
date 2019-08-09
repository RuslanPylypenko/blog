<?php


namespace App\Services;


use App\Repositories\ArticleRepository;

class ArticleService
{

    /**
     * @var ArticleRepository
     */
    private $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->repository->findOne($id);
    }

    /**
     * @param int $perPage
     * @return mixed
     */
    public function get($perPage = 12)
    {
        return $this->repository->get($perPage);
    }

    public function addView($id)
    {
        $article = $this->repository->findOne($id);
        return $this->repository->update($id, ['views' => ($article->views + 1)]);
    }

    public function likeArticle($id)
    {
        $article = $this->repository->findOne($id);
        return $this->repository->update($id, ['likes' => ($article->likes + 1)]);
    }
}