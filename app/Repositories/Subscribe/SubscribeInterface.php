<?php


namespace App\Repositories\Subscribe;


interface SubscribeInterface
{

    /**
     * @param $follower_id
     * @param $subscriber_id
     */
    public function setSubscriber($follower_id, $subscriber_id);

    /**
     * @param $follower_id
     * @param $subscriber_id
     */
    public function unsetSubscriber($follower_id, $subscriber_id);

}