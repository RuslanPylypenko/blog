<?php


namespace App\Services;


use App\Exceptions\CommentException;
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

    /**
     * @param $comment_id
     * @param $user_id
     * @return mixed
     * @throws CommentException
     */
    public function delete($comment_id, $user_id)
    {
        if ($this->repository->isExist($comment_id)) {
            if (!$this->repository->hasAccess($comment_id, $user_id)) {
                throw new CommentException('Это не Ваш комментарий');
            }
            return $this->repository->delete($comment_id, $user_id);
        }
        throw new CommentException('Комментарий не найден');
    }
}