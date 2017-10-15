<?php

namespace FullyStudios\LaravelTeams\Traits;

use FullyStudios\LaravelTeams\Team;
use FullyStudios\LaravelTeams\TeamInvite;

trait UserTeams
{
    
    // If image is not the original image, get the original
    public function ownedTeams()
    {
        return $this->teams->where('owner_id', $this->id)->get();
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
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
        $this->teams()->attach($team);
    }
    
    public function removeFromTeam($team)
    {
        if ($team instanceof Team) {
            $team = $team->id;
        }
        $this->teams()->detach($team);
    }
}
