<?php

namespace FullyStudios\LaravelTeams\Traits;

use FullyStudios\LaravelTeams\Models\Team;
use FullyStudios\LaravelTeams\Models\TeamInvite;

trait UserTeams
{
    
    // If image is not the original image, get the original
    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function memberTeams()
    {
        return $this->belongsToMany(Team::class);
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
        $invite = TeamInvite::where(['user_id' => $this->id, 'team_id' => $team])->firstOrFail();
        return $invite;
    }

    public function inviteToTeam($team)
    {
        if ($team instanceof Team) {
            $team = $team->id;
        }
        $teamInvite = new TeamInvite;

        TeamInvite::firstOrCreate([
            'user_id' => $this->id,
            'team_id' => $team
        ]);
        
        return $this;
    }

    public function addToTeam($team)
    {
        if ($team instanceof Team) {
            $team = $team->id;
        }
        $this->memberTeams()->attach($team);
    }
    
    public function removeFromTeam($team)
    {
        if ($team instanceof Team) {
            $team = $team->id;
        }
        $this->memberTeams()->detach($team);
    }
}
