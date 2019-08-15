<?php


namespace App\Services;


use App\Repositories\UserRepository;
use App\User;

class UserService
{

    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
        if (!$user) throw new \Exception('Нет такого пользователя!');

        return $this->userRepository->setSubscriber($user->id, $cUser->id);
    }

    /**
     * @param User $user
     * @param User $cUser
     * @throws \Exception
     */
    public function unsubscribe(User $user, User $cUser)
    {
        if (!$user) throw new \Exception('Нет такого пользователя!');

        return $this->userRepository->unsetSubscriber($user->id, $cUser->id);
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