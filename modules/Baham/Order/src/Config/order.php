<?php

return [
    /*
     * Enter the time period you want the list of orders to be stored in the cache in minutes
     */
    "order_cache_time" => env('ORDER_CACHE_TIME' , 1),

    /*
     * Enter the registration cost of each order in units of coins
     */
    "order_registration_cost" => env('ORDER_REGISTRATION' , 4),

    /*
     * Enter the reward of each follow by the user into the coin unit
     */
    "order_follow_reward" => env('ORDER_FOLLOW_REWARD' , 2),




];
