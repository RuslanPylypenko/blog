<?php


namespace App\Repositories\OrderArticle;

use Illuminate\Support\Facades\Redis;

class RedisOrderArticle implements OrderArticleInterface
{
    /**
     * @param $articleId
     * @param $userId
     */
    public function addArticleToUser($articleId, $userId)
    {
        Redis::sadd("user:{$userId}:articles:buy", $articleId);
        Redis::sadd("article:{$articleId}:users:buy", $userId);
    }

    /**
     * @param $articleId
     * @param $userId
     * @return bool
     */
    public function hasUserArticle($articleId, $userId)
    {
        return Redis::sismember("article:{$articleId}:users:buy", $userId);
    }

    /**
     * @param $articleId
     * @return array
     */
    public function whoBuyArticle($articleId)
    {
        return Redis::smembers("article:{$articleId}:users:bye");
    }
}