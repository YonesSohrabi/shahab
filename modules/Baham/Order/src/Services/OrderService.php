<?php

namespace Baham\Order\Services;

use Baham\Order\Contract\OrderRepositoryInterface;
use Baham\Transaction\Models\Transaction;
use Baham\User\Contract\UserRepositoryInterface;
use Baham\User\Models\User;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getOrdersForUserToFollow(User $user)
    {
        return $this->orderRepository->getOrdersForUserToFollow($user->id);
    }

    public function followOrder(User $user, int $orderId)
    {
        $rewardPrice = config('OrderConfig.order_follow_reward');
        $this->orderRepository->decrementRemainingOrders($orderId);
        $this->orderRepository->createTransaction($user->id, $orderId, Transaction::TYPE_REWARD, $rewardPrice);
        resolve(UserRepositoryInterface::class)
            ->increaseCoins($user, $rewardPrice);

    }
}
