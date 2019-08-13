<?php


namespace App\Repositories;


class CommentRepository extends ARepository
{
    public function get($article_id)
    {
        return $this->model->orderBy('created_at', 'desc')
            ->where('article_id', $article_id)
            ->get();
    }
}