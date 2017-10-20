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
            'accepted_at' => null
        ]);
    }

    public function test_if_a_user_can_belong_to_multiple_teams ()
    {
        $user = fsltCreateUser();
        $team1 = fsltCreateTeam();
        $team2 = fsltCreateTeam();
        $user->addToTeam($team1);
        $user->addToTeam($team2);
        $this->assertEquals(2, $user->memberTeams->count());
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

    public function test_if_a_user_can_retrieve_owned_teams ()
    {
        $user = fsltCreateUser();
        $team1 = fsltCreateTeam();
        $team2 = fsltCreateTeam();
        $team3 = fsltCreateTeam(['owner_id' => 2]);

        $ownedTeams = $user->ownedTeams;
        $this->assertTrue($ownedTeams->contains($team1));
        $this->assertTrue($ownedTeams->contains($team2));
        $this->assertFalse($ownedTeams->contains($team3));
    }

    public function test_if_a_user_can_retrieve_teams_where_user_is_member ()
    {
        $user = fsltCreateUser();
        $team1 = fsltCreateTeam(['owner_id' => 2]);
        $team2 = fsltCreateTeam(['owner_id' => 2]);
        $team3 = fsltCreateTeam(['owner_id' => 2]);
        $user->addToTeam($team1);
        $user->addToTeam($team2);
        $memberTeams = $user->memberTeams;
        $this->assertTrue($memberTeams->contains($team1));
        $this->assertTrue($memberTeams->contains($team2));
        $this->assertFalse($memberTeams->contains($team3));
    }

    public function test_if_a_user_can_retrieve_all_teams_where_user_is_member_or_owner ()
    {
        $user = fsltCreateUser();
        $notUserId = $user->id +1;
        $team1 = fsltCreateTeam(['owner_id' => $notUserId]);
        $team2 = fsltCreateTeam(['owner_id' => $notUserId]);
        $team3 = fsltCreateTeam(['owner_id' => $user->id]);
        $team4 = fsltCreateTeam(['owner_id' => $notUserId]);
        $user->addToTeam($team1);
        $user->addToTeam($team2);
        $memberTeams = $user->allTeams;
        $this->assertTrue($memberTeams->contains($team1));
        $this->assertTrue($memberTeams->contains($team2));
        $this->assertTrue($memberTeams->contains($team3));
        $this->assertFalse($memberTeams->contains($team4));
    }

}