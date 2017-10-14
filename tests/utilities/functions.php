<?php

use App\User;

if (!function_exists('fsltCreateUser')) {
    function fsltCreateUser($vars = [])
    {
        $password = 'secret';
        $name = 'Tester Person '.rand(10000, 99999);
        $email = 
        $defaults = [
            'name' => $name,
            'password' => $password,
            'email' => snake_case($name)."@testingemail.se"
        ];
        
        $properties = array_merge($defaults, $vars);
        $properties['password'] = bcrypt($properties['password']);

        $user = new User();
        $user->fill($properties);
        $user->save();
        
        return $user;
    }

}
