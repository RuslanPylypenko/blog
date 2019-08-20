<?php


namespace App\Services;

use App\Repositories\PointsRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Support\Facades\DB;

class PointService
{
    const REGISTRATION_TYPE_OPERATION = 'for_register';
    const POINTS_FOR_REGISTER = 100;

    const TYPE_OPERATION_UP = 'up';
    const TYPE_OPERATION_DOWN = 'down';

    /**
     * @var PointsRepository
     */
    private $pointsRepository;

    /**
     * @var UserRepository
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

    /**
     * @param User $user
     * @param User $cUser
     * @param $data
     * @throws \Exception
     */
    public function sendPointTransaction(User $user, User $cUser, $data)
    {
        if (!$this->isAvailablePoints($cUser, $data['points'])) {
            throw new \Exception('У Вас недостаточно на счету!');
        }

        try {
            DB::beginTransaction();

            $this->addUserPoints($user, $data);
            $this->removeUserPoints($cUser, $data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * @param User $user
     * @param $points
     * @return bool
     */
    private function isAvailablePoints(User $user, $points)
    {
        return $user->points > $points;
    }

    /**
     * @param User $user
     * @param array $data
     */
    private function removeUserPoints(User $user, array $data)
    {
        $this->userRepository->update($user->id, ['points' => ($user->points -= $data['points'])]);

        $this->pointsRepository->create([
            'amount' => -($data['points']),
            'user_id' => $user->id,
            'type_operation' => self::TYPE_OPERATION_DOWN,
            'message' => $data['message'],
            'points_after_transaction' => $user->points
        ]);
    }

    /**
     * @param User $user
     * @param array $data
     */
    private function addUserPoints(User $user, array $data)
    {
        $this->userRepository->update($user->id, ['points' => ($user->points += $data['points'])]);

        $this->pointsRepository->create([
            'amount' => $data['points'],
            'user_id' => $user->id,
            'type_operation' => self::TYPE_OPERATION_UP,
            'message' => $data['message'],
            'points_after_transaction' => $user->points
        ]);
    }
}