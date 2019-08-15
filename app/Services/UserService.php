<?php


namespace App\Services;


use App\Repositories\UserRepository;

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


    public function get()
    {
        return $this->userRepository->get();
    }

}