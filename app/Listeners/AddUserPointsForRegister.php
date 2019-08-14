<?php


namespace App\Listeners;

use App\Services\PointService;
use Illuminate\Auth\Events\Registered;

class AddUserPointsForRegister
{
    /**
     * @var PointService
     */
    private $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    public function handle(Registered $event)
    {
        $user = $event->user;
        $this->pointService->addPointsForRegistration($user);
    }
}