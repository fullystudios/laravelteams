<?php

namespace FullyStudios\LaravelTeams\Traits;

use Carbon\Carbon;
use FullyStudios\LaravelTeams\Models\Team;
use FullyStudios\LaravelTeams\Models\TeamInvite;

trait UserTeams
{
    
    // If image is not the original image, get the original
    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function teams()
    {
        $userModel = config()->get('auth.providers.users.model');

        return $this->belongsToMany(Team::class, 'team_invites', 'user_id', 'team_id')
            ->as('invite')
            ->withPivot('accepted_at')
            ->whereNotNull('accepted_at')
            ->withTimeStamps();
    }

    public function pendingTeams()
    {
        $userModel = config()->get('auth.providers.users.model');

        return $this->belongsToMany(Team::class, 'team_invites', 'user_id', 'team_id')
            ->as('invite')
            ->whereNull('accepted_at')
            ->withTimeStamps();
    }

    public function getAllTeamsAttribute()
    {
        $this->load('ownedTeams', 'memberTeams');
        return $this->ownedTeams->merge($this->memberTeams);
    }

    public function invite($team)
    {
        if ($team instanceof Team) {
            $team = $team->id;
        }
        // if (is_string($team)) {
        //     $team = Team::find($team);
        // }
        // $this->teams()->save($team);
        $team = $this->teams->where('id', $team)->first();
        return $team->invite;
    }

    public function inviteToTeam($team)
    {
        if (is_string($team)) {
            $team = Team::find($team);
        }
        $this->teams()->syncWithoutDetaching([$team->id]);
        
        return $team;
    }

    public function addToTeam($team)
    {
        if (is_string($team)) {
            $team = Team::find($team);
        }
        $this->teams()->syncWithoutDetaching([$team->id => ['accepted_at' => Carbon::now()]]);
        return $this;
    }
    
    public function removeFromTeam($team)
    {
        if ($team instanceof Team) {
            $team = $team->id;
        }
        $this->teams()->detach($team);
    }
}
