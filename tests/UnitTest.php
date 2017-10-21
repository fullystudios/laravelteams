<?php
use Carbon\Carbon;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UnitTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        //  ../../../vendor/bin/phpunit
        require './vendor/autoload.php';
        parent::setUp();
    }

    public function test_if_a_user_is_added_to_team_when_accepting_invite ()
    {
        $user = fsltCreateUser();
        $team = fsltCreateTeam();
        $user->inviteToTeam($team);
        $user->invite($team)->accept();
        $this->assertDatabaseHas('team_user', [
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);
    }

    public function test_if_team_invites_are_accepted ()
    {
        $acceptedInviteA = fsltCreateTeamInvite();
        $acceptedInviteB = fsltCreateTeamInvite(['accepted_at' => Carbon::parse('-1 day')]);
        $pendingInviteB = fsltCreateTeamInvite();
    }

    public function test_if_team_invites_are_pending ()
    {
        
    }

}