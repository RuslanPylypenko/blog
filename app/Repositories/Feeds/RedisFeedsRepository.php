<?php


namespace App\Repositories\Feeds;

use Illuminate\Support\Facades\Redis;

class RedisFeedsRepository implements FeedsRepositoryInterface
{
    /**
     * @param $userId
     * @return array
     */
    public function get($userId)
    {
        return Redis::smembers("feeds:{$userId}:articles");
    }


    /**
     * @param $articleId
     * @param $userId
     */
    public function save($articleId, $userId)
    {
        Redis::sadd("feeds:{$userId}:articles", $articleId);
    }
}