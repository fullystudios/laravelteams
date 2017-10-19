<?php

use App\User;
use FullyStudios\LaravelTeams\Models\Team;
use FullyStudios\LaravelTeams\Models\TeamInvite;





if (!function_exists('fsltCreateUser')) {
    function fsltCreateUser($vars = [])
    {
        $password = 'secret';
        $name = 'Tester Person '.rand(10000, 99999);
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

if (!function_exists('fsltCreateTeam')) {
    function fsltCreateTeam($vars = [])
    {
        $defaults = [
            'name' => 'Team '.rand(10000, 99999),
            'owner_id' => 1
        ];
        $properties = array_merge($defaults, $vars);
        $team = new Team();
        $team->fill($properties);
        $team->save();
        
        return $team;
    }
}


if (!function_exists('fsltCreateTeamInvite')) {
    function fsltCreateTeamInvite($vars = [])
    {
        $defaults = [
            'team_id' => 1,
            'user_id' => 1
        ];
        $properties = array_merge($defaults, $vars);
        $teamInvite = new TeamInvite();
        $teamInvite->fill($properties);
        $teamInvite->save();
        
        return $teamInvite;
    }
}
