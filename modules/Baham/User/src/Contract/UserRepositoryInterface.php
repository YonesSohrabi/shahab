<?php

namespace Baham\User\Contract;

use Baham\User\Models\User;

interface UserRepositoryInterface
{

    public function findByUserName(string $username);

    public function firstOrCreateByUserName(int $username);

    public function find(int $userId);

    public function increaseCoins(User $user,int $count);

    public function decreaseCoins(User $user,int $count);


}
