<?php
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use FullyStudios\LaravelTeams\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class IntegrationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        //  ../../../vendor/bin/phpunit
        require './vendor/autoload.php';
        parent::setUp();
    }

    public function test_if_teams_are_attached_twice_for_invite_and_add ()
    {
        $userA = fsltCreateUser();
        $userB = fsltCreateUser();

        $this->be($userA);
        $team = fsltCreateTeam();
        $team->invite($userB);
        $userB->addToTeam($team);

        $this->assertDatabaseMissing('team_invites', [
            'user_id' => $userB->id,
            'team_id' => $team->id,
            'accepted_at' => null 
        ]);
    }

    public function test_if_team_invites_are_pending ()
    {
        $userA = fsltCreateUser();
        $userB = fsltCreateUser();

        $this->be($userA);
        $team = fsltCreateTeam();
        $team->invite($userB);

        $this->assertTrue($team->invitedUsers->contains($userB));
        $this->assertFalse($team->invitedUsers->contains($userA));
    }

    public function test_if_a_user_can_belong_to_multiple_teams ()
    {
        $userA = fsltCreateUser();
        $userB = fsltCreateUser();
        
        $this->be($userA);
        
        $team1 = fsltCreateTeam();
        $team2 = fsltCreateTeam();

        $userB->addToTeam($team1);
        $userB->addToTeam($team2);
        $this->assertTrue($userB->teams->contains($team1));
        $this->assertTrue($userB->teams->contains($team2));
    }

    public function test_if_a_user_can_be_removed_from_a_team ()
    {
        $user = fsltCreateUser();
        $this->be($user);
        $team = fsltCreateTeam();
        $user->addToTeam($team);
        $user->removeFromTeam($team);
        $this->assertDatabaseMissing('team_invites', [
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);
    }

    public function test_if_a_user_can_retrieve_owned_teams ()
    {
        $user = fsltCreateUser();
        $this->be($user);
        $team1 = fsltCreateTeam();
        $team2 = fsltCreateTeam();
        $team3 = fsltCreateTeam();
        
        $team1->owner_id = 2;
        $team1->save();

        $ownedTeams = $user->ownedTeams;
        $this->assertFalse($ownedTeams->contains($team1));
        $this->assertTrue($ownedTeams->contains($team2));
        $this->assertTrue($ownedTeams->contains($team3));
    }

    public function test_if_a_user_can_get_a_specific_team_invite ()
    {
        $userA = fsltCreateUser();
        $userB = fsltCreateUser();

        $this->be($userA);
        $team = fsltCreateTeam();
        $team->invite($userB);
        $this->assertTrue($userB->invite($team) instanceof Team);
    }


}