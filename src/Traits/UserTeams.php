<?php

namespace FullyStudios\LaravelTeams\Traits;

use Carbon\Carbon;
use FullyStudios\LaravelTeams\Models\Team;

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

    public function allTeams()
    {
        $userModel = config()->get('auth.providers.users.model');

        return $this->belongsToMany(Team::class, 'team_invites', 'user_id', 'team_id')
            ->as('invite')
            ->withTimeStamps();
    }

    public function teamVites()
    {
        $userModel = config()->get('auth.providers.users.model');

        return $this->belongsToMany(Team::class, 'team_invites', 'user_id', 'team_id')
            ->as('invite')
            ->withTimeStamps();
    }

    public function getAllTeamsAttribute()
    {
        $this->load('teams', 'pendingTeams');
        return $this->teams->merge($this->pendingTeams);
    }

    // Get specific invite
    public function invite($team)
    {
        if ($team instanceof Team) {
            $team = $team->id;
        }
        $team = $this->allTeams->where('id', $team)->first();
        return $team->invite;
    }

    // Get all teams that has not been accepted
    public function invites()
    {
        $userModel = config()->get('auth.providers.users.model');
        return $this->belongsToMany(Team::class, 'team_invites', 'user_id', 'team_id')
            ->as('invite')
            ->whereNull('accepted_at')
            ->withTimeStamps();
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

    public function scopeNotInTeam($query, $team)
    {
        $team_id = $team->id;
        return $query->where(function ($q) use ($team_id) {
            $q->whereHas('teamVites', function ($q) use ($team_id) {
                $q->whereNotIn('team_invites.team_id', [$team_id]);
            });
        })->orWhere(function ($q) {
            $q->whereDoesntHave('teamVites');
        });
    }
}
