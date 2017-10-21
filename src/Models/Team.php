<?php

namespace FullyStudios\LaravelTeams\Models;

use Illuminate\Database\Eloquent\Model;
use FullyStudios\LaravelTeams\Models\TeamInvite;

class Team extends Model
{
    protected $fillable = ['owner_id', 'name'];

    public static function boot () {
        static::created(function($team) {
            $team->owner_id = \Auth::id();
            \Auth::user()->addToTeam($team);
        });
    }

    public function owner()
    {
        return $this->belongsTo(config()->get('auth.providers.users.model'), 'owner_id');
    }

    public function members()
    {
        $userModel = config()->get('auth.providers.users.model');

        return $this->belongsToMany($userModel, 'team_invites', 'team_id', 'user_id')
            ->as('invite')
            ->whereNotNull('accepted_at')
            ->withTimeStamps();
    }

    // Path helper for model
    public function path(String $name = 'show')
    {
        return route("teams.{$name}", [$this]);
    }

    public function invite($user)
    {
        $userModel = config()->get('auth.providers.users.model');
        if ($user instanceof $userModel) {
            $user = $user->id;
        }
        $this->members()->attach([$user]);
        return $this;
    }

    public function invitedUsers()
    {
        $userModel = config()->get('auth.providers.users.model');
        return $this->belongsToMany($userModel, 'team_invites', 'team_id', 'user_id')
            ->as('invite')
            ->whereNull('accepted_at')
            ->withTimeStamps();
    }
}