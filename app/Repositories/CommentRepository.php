<?php


namespace App\Repositories;


class CommentRepository extends ARepository
{
    /**
     * @param $article_id
     * @return mixed
     */
    public function get($article_id)
    {
        return $this->model->orderBy('created_at', 'desc')
            ->where('article_id', $article_id)
            ->get();
    }


    /**
     * @param $comment_id
     * @return mixed
     */
    public function delete($comment_id)
    {
        return $this->model->where('id', $comment_id)->delete();
    }

    /**
     * @param $comment_id
     * @return mixed
     */
    public function isExist($comment_id)
    {
       return $this->exists(['id' => $comment_id]);
    }


    /**
     * @param $comment_id
     * @param $user_id
     * @return mixed
     */
    public function hasAccess($comment_id, $user_id)
    {
       return $this->exists(['id' => $comment_id, 'user_id' => $user_id]);
    }
}