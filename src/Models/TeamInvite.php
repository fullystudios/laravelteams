<?php

namespace FullyStudios\LaravelTeams\Models;

use App\User;
use Carbon\Carbon;
use FullyStudios\LaravelTeams\Models\Team;
use Illuminate\Database\Eloquent\Model;

class TeamInvite extends Model
{
    protected $fillable = ['user_id', 'team_id', 'accepted'];
    protected $dates = ['accepted'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function accept()
    {
        $this->accepted = Carbon::now();
        $this->save();

        $this->user->addToTeam($this->team);
        
        return $this;
    }

    // Path helper for model
    public function path(String $name = 'show')
    {
        return route("teams.{$name}", [$this]);
    }
}