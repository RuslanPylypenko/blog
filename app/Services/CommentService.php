<?php


namespace App\Services;


use App\Repositories\CommentRepository;

class CommentService
{

    /** @var CommentRepository */
    private $repository;

    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $comment
     * @return mixed
     */
    public function createComment(array $comment)
    {
        return $this->repository->create($comment);
    }

    /**
     * @param $article_id
     * @return mixed
     */
    public function get($article_id)
    {
        return $this->repository->get($article_id);
    }
}