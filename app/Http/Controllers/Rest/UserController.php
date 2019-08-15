<?php


namespace App\Http\Controllers\Rest;


use App\Http\Controllers\Controller;
use App\Services\PointService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @var PointService
     */
    private $pointService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * UserController constructor.
     * @param PointService $pointService
     * @param UserService $userService
     */
    public function __construct(
        PointService $pointService,
        UserService $userService
    )
    {
        $this->userService = $userService;
        $this->pointService = $pointService;
    }

    /**
     * Показать список пользователей
     * @return array
     */
    public function index()
    {
        $users = $this->userService->get(); // todo реализовать пагинацию

        return [
            'success' => true,
            'users' => $users
        ];
    }


    /**
     * Отправить очки пользователю
     * @param $user_id
     * @param Request $request
     * @return array
     */
    public function sendPoints($user_id, Request $request)
    {
        $data = [
            'points' => $request->input('points'),
            'message' => $request->input('message', null)
        ];
        $this->sentPointsValidate($data);

        try {
            $cUser = Auth::guard()->user();
            $User = $this->userService->find($user_id);

            if (!$User) throw new \Exception('Нет такого пользователя!');

            $this->pointService->sendPointTransaction($User, $cUser, $data);

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

    }

    /**
     * Подписаться на пользователя
     * @param $user_id
     * @return array
     */
    public function subscribe($user_id)
    {
        try {
            $cUser = Auth::guard()->user();
            $user = $this->userService->find($user_id);

            if (!$user) throw new \Exception('Нет такого пользователя!');

            $this->userService->subscribe($user, $cUser);
            return ['success' => true, 'message' => 'OK'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

    }


    /**
     * Отписаться от пользователя
     * @param $user_id
     * @return array
     */
    public function unsubscribe($user_id)
    {
        try {
            $cUser = Auth::guard()->user();
            $user = $this->userService->find($user_id);

            $this->userService->unsubscribe($user, $cUser);
            return ['success' => true, 'message' => 'OK'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * @param $user_id
     * @return array
     */
    public function getSubscribers($user_id)
    {
        try {
            $user = $this->userService->find($user_id);

            if (!$user) throw new \Exception('Нет такого пользователя!');

            $users = $this->userService->getSubscribers($user);
            return ['success' => true, 'users' => $users];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    /**
     * @param $user_id
     * @return array
     */
    public function getFollowers($user_id)
    {
        try {
            $user = $this->userService->find($user_id);

            if (!$user) throw new \Exception('Нет такого пользователя!');

            $users = $this->userService->getFollowers($user);
            return ['success' => true, 'users' => $users];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Валидация отправки монет
     * @param $data
     */
    private function sentPointsValidate($data)
    {
        Validator::make(
            $data,
            ['points' => 'required|integer|min:1']
        )->validate();
    }
}