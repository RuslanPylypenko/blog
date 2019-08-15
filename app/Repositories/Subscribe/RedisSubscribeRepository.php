<?php


namespace App\Repositories\Subscribe;


use Illuminate\Support\Facades\Redis;

class RedisSubscribeRepository implements SubscribeInterface
{

    /**
     * @param $follower_id
     * @param $subscriber_id
     */
    public function setSubscriber($follower_id, $subscriber_id)
    {
        Redis::set("user:{$follower_id}:followers", $subscriber_id);
        Redis::set("user:{$subscriber_id}:subscribers", $follower_id);
    }

    /**
     * @param $follower_id
     * @param $subscriber_id
     */
    public function unsetSubscriber($follower_id, $subscriber_id)
    {
        Redis::del("user:{$follower_id}:followers", $subscriber_id);
        Redis::del("user:{$subscriber_id}:subscribers", $follower_id);
    }
}