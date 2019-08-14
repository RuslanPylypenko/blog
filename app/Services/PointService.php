<?php


namespace App\Services;

use App\Repositories\PointsRepository;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Support\Facades\Auth;
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

                DB::rollBack();

            } catch (\Exception $e) {
                DB::rollBack();
            }

        }
    }

    /**
     * @param $user_id
     * @param $data
     * @throws \Exception
     */
    public function sendPointTransaction($user_id, $data)
    {

        $cUser = Auth::guard()->user();

        $user = $this->userRepository->find($user_id);

        if(!$user)  throw new \Exception('Нет такого пользователя!');

        if ($this->isAvailablePoints($cUser, $data['points'])) {
            try {
                DB::beginTransaction();

                $this->removeUserPoints($cUser, $data);
                $this->addUserPoints($user, $data);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }else{
            throw new \Exception('У Вас недостаточно на счету!');
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