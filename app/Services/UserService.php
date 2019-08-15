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
     * @param User $User
     * @param User $cUser
     * @return mixed
     */
    public function subscribe(User $User, User $cUser)
    {
        return $this->userRepository->setSubscriber($User->id, $cUser->id);
    }

    /**
     * @param User $User
     * @param User $cUser
     * @return mixed
     */
    public function unsubscribe(User $User, User $cUser)
    {
        return $this->userRepository->unsetSubscriber($User->id, $cUser->id);
    }
}