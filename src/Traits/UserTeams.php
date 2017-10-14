<?php

namespace FullyStudios\LaravelTeams\Traits;

use FullyStudios\LaravelTeams\Team;

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

}
