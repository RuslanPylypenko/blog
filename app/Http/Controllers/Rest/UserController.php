<?php


namespace App\Http\Controllers\Rest;


use App\Http\Controllers\Controller;
use App\Services\PointService;
use App\Services\UserService;
use Illuminate\Http\Request;
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
            $this->pointService->sendPointTransaction($user_id, $data);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

    }

    private function sentPointsValidate($data)
    {
        Validator::make(
            $data,
            ['points' => 'required|integer|min:1']
        )->validate();
    }
}