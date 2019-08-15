<?php


namespace App\Repositories;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;

class UserRepository extends ARepository
{

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->model->select(['id', 'name', 'email'])->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->model->where('id', $id)->firstOrFail();
    }


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