<?php


namespace App\Services;


use App\Repositories\Subscribe\SubscribeInterface;
use App\Repositories\UserRepository;
use App\User;

class UserService
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var SubscribeInterface
     */
    private $subscribeRepository;


    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param SubscribeInterface $subscribeRepository
     */
    public function __construct(
        UserRepository $userRepository,
        SubscribeInterface $subscribeRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->subscribeRepository = $subscribeRepository;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->userRepository->find($id);
    }


    /**
     * @return mixed
     */
    public function get()
    {
        return $this->userRepository->get();
    }

    /**
     * @param User $user
     * @param User $cUser
     * @throws \Exception
     */
    public function subscribe(User $user, User $cUser)
    {
        if ($this->isUserEquivalent($user, $cUser)) throw new \Exception('Невозможно подписаться на себя!');
        return $this->subscribeRepository->setSubscriber($user->id, $cUser->id);
    }

    /**
     * @param User $user
     * @param User $cUser
     * @throws \Exception
     */
    public function unsubscribe(User $user, User $cUser)
    {
        if (!$user) throw new \Exception('Нет такого пользователя!');
        return $this->subscribeRepository->unsetSubscriber($user->id, $cUser->id);
    }


    /**
     * @param User $user
     * @return array
     * @throws \Exception
     */
    public function getSubscribers(User $user)
    {
        if (!$user) throw new \Exception('Нет такого пользователя!');
        $user_ids = $this->subscribeRepository->getSubscribers($user->id);
        return $this->userRepository->getByUserIds($user_ids);
    }


    /**
     * @param User $user
     * @return array
     * @throws \Exception
     */
    public function getFollowers(User $user)
    {
        if (!$user) throw new \Exception('Нет такого пользователя!');
        $user_ids = $this->subscribeRepository->getFollowers($user->id);
        return $this->userRepository->getByUserIds($user_ids);
    }


    /**
     * @param User $user
     * @param User $cUser
     * @return bool
     */
    private function isUserEquivalent(User $user, User $cUser)
    {
        return $cUser->id === $user->id;
    }
}