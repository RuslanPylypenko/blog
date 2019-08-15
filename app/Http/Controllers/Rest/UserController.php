<?php


namespace App\Http\Controllers\Rest;


use App\Http\Controllers\Controller;
use App\Services\PointService;
use App\Services\UserService;
use App\User;
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

    public function index()
    {
        $users = $this->userService->get();

        return [
            'success' => true,
            'users' => $users
        ];
    }


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

    public function subscribe($user_id)
    {
        try {
            $cUser = Auth::guard()->user();
            $user = $this->userService->find($user_id);

            if ($this->isUserEquivalent($user, $cUser)) throw new \Exception('Невозможно подписаться на себя!');
            if (!$user) throw new \Exception('Нет такого пользователя!');

            $this->userService->subscribe($user, $cUser);
            return ['success' => true, 'message' => 'OK'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

    }


    public function unsubscribe($user_id)
    {
        try {
            $cUser = Auth::guard()->user();
            $user = $this->userService->find($user_id);

            if (!$user) throw new \Exception('Нет такого пользователя!');

            $this->userService->unsubscribe($user, $cUser);
            return ['success' => true, 'message' => 'OK'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

    }

    private function isUserEquivalent(User $user, User $cUser)
    {
        return $cUser->id === $user->id;
    }

    private function sentPointsValidate($data)
    {
        Validator::make(
            $data,
            ['points' => 'required|integer|min:1']
        )->validate();
    }
}