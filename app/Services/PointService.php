<?php


namespace App\Services;


use App\Events\PointTransactionEvent;
use App\Repositories\PointsRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Support\Facades\DB;

class PointService
{
    const REGISTRATION_TYPE_OPERATION = 'for_register';
    const POINTS_FOR_REGISTER = 100;

    /**
     * @var PointsRepository
     */
    private $pointsRepository;

    /**
     * @var User
     */
    private $userRepository;

    public function __construct(
        PointsRepository $pointsRepository,
        UserRepository $userRepository
    )
    {
        $this->pointsRepository = $pointsRepository;
        $this->userRepository = $userRepository;
    }

    public function addPointsForRegistration(User $user)
    {
        $isEarned = $this->pointsRepository->exists([
            'user_id' => $user->id,
            'type_operation' => self::REGISTRATION_TYPE_OPERATION
        ]);

        if (!$isEarned) {

            try {
                DB::beginTransaction();

                $this->userRepository->update($user->id, ['points' => ($user->points += self::POINTS_FOR_REGISTER)]);
                $this->pointsRepository->create([
                    'amount' => self::POINTS_FOR_REGISTER,
                    'user_id' => $user->id,
                    'type_operation' => self::REGISTRATION_TYPE_OPERATION,
                    'message' => 'hello world',
                    'points_after_transaction' => $user->points
                ]);

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
            }

        }
    }
}