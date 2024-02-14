<?php

namespace Baham\Order\Contract;

interface OrderRepositoryInterface
{

    public function create(int $count);

    public function getOrdersForUserToFollow(int $userId);

    public function decrementRemainingOrders(int $orderId);

    public function updateOrderStatusToClosed(int $orderId);

    public function hideOrderForUser(int $orderId, int $userId);

    public function createTransaction(int $userId, int $orderId , int $type ,int $count);

}
