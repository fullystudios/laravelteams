<?php
use Tests\TestCase;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IntegrationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        //  ../../../vendor/bin/phpunit
        require './vendor/autoload.php';
        parent::setUp();
    }

    public function test_if_a_user_can_be_invited_to_a_team ()
    {
        $user = fsltCreateUser();
        $team = fsltCreateTeam(['owner_id' => $user->id]);
        $user->inviteToTeam($team->id);
        $this->assertDatabaseHas('team_invites', [
            'team_id' => $team->id,
            'user_id' => $user->id,
            'accepted' => null
        ]);
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

    public function test_if_a_user_can_belong_to_multiple_teams ()
    {
        $user = fsltCreateUser();
        $team1 = fsltCreateTeam();
        $team2 = fsltCreateTeam();
        $user->addToTeam($team1);
        $user->addToTeam($team2);
        $this->assertEquals(2, $user->teams->count());
    }

    public function test_if_a_user_can_be_removed_from_a_team ()
    {
        $user = fsltCreateUser();
        $team = fsltCreateTeam();
        $user->addToTeam($team);
        $user->removeFromTeam($team);
        $this->assertDatabaseMissing('team_user', [
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);  
    }

}