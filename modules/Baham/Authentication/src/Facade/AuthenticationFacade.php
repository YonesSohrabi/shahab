<?php


namespace Baham\Authentication\Facade;

use Illuminate\Support\Facades\Facade;


/**
 * Class LaravelAuthenticationFacade
 *
 * @package Tarazo\LaravelAuthentication\Facade
 */
class AuthenticationFacade extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return 'Authentication';
    }

}
