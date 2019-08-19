<?php


namespace App\Repositories\Feeds;


interface FeedsRepositoryInterface
{
    /**
     * @param $userId
     * @return array
     */
    public function get($userId);

    /**
     * @param $articleId
     * @param $userId
     */
    public function save($articleId, $userId);
}