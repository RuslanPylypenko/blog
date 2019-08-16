<?php


namespace App\Services;


use App\Exceptions\CommentException;
use App\Repositories\CommentRepository;
use App\User;

class CommentService
{

    /** @var CommentRepository */
    private $repository;

    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param User $cUser
     * @param array $comment
     * @return mixed
     */
    public function createComment(User $cUser, array $comment)
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
     * @param User $user
     * @param $comment_id
     * @return bool
     */
    public function hasUserAccessComment(User $user, $comment_id)
    {
        return $this->repository->exists(['id' => $comment_id, 'user_id' => $user->id]);
    }


    /**
     * @param $comment_id
     * @param $user_id
     * @return mixed
     */
    public function delete($comment_id)
    {
        return $this->repository->delete($comment_id);
    }
}