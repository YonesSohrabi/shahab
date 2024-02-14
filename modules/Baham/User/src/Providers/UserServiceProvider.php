<?php
namespace Baham\User\Providers;

use Baham\User\Contract\UserRepositoryInterface;
use Baham\User\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->mergeConfigFrom(__DIR__ . '/../Config/user.php', 'UserConfig');


        $this->app->bind(UserRepositoryInterface::class, function () {
            return resolve(EloquentUserRepository::class);
        });
    }

}
