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
        Redis::sadd("user:{$follower_id}:followers", $subscriber_id);
        Redis::sadd("user:{$subscriber_id}:subscribers", $follower_id);
    }

    /**
     * @param $follower_id
     * @param $subscriber_id
     */
    public function unsetSubscriber($follower_id, $subscriber_id)
    {
        Redis::srem("user:{$follower_id}:followers", $subscriber_id);
        Redis::srem("user:{$subscriber_id}:subscribers", $follower_id);
    }

    /**
     * @param $user_id
     * @return array
     */
   public function getSubscribers($user_id)
   {
      return Redis::smembers("user:{$user_id}:subscribers");
   }

    /**
     * @param $user_id
     * @return array
     */
    public function getFollowers($user_id)
    {
        return Redis::smembers("user:{$user_id}:followers");
    }
}