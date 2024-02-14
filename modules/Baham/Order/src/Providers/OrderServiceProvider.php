<?php
namespace Baham\Order\Providers;

use Baham\Order\Contract\OrderRepositoryInterface;
use Baham\Order\Repository\EloquentOrderRepository;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/order.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->mergeConfigFrom(__DIR__ . '/../Config/order.php', 'OrderConfig');
        $this->app->bind(OrderRepositoryInterface::class, function () {
            return resolve(EloquentOrderRepository::class);
        });

    }

    public function boot()
    {
    }
}
