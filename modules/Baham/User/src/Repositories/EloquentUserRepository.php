<?php
namespace Baham\User\Repositories;


use Baham\User\Contract\UserRepositoryInterface;
use Baham\User\Models\User;


class EloquentUserRepository implements UserRepositoryInterface
{
    private $user;

    public function __construct()
    {
        $this->user = User::query();
    }

    public function findByUserName($username)
    {
        return $this->user->where('user_name',$username);
    }

    public function firstOrCreateByUserName($username)
    {
        return $this->findByUserName($username)->firstOrCreate([
            'user_name' => $username,
        ]) ;
    }

    public function find($userID)
    {
        return $this->user->find($userID);
    }

    public function increaseCoins($user,$count)
    {
        $user->coins += $count;
        $user->save();
        return $user->coins;
    }

    public function decreaseCoins($user,$count)
    {
        $user->coins -= $count;
        $user->save();
        return $user->coins;
    }



}
