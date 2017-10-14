<?php

namespace FullyStudios\LaravelTeams;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['owner_id', 'name'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Path helper for model
    public function path(String $name = 'show')
    {
        return route("teams.{$name}", [$this]);
    }
}