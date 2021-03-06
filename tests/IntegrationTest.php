<?php
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IntegrationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        //  ../../../vendor/bin/phpunit
        require './vendor/autoload.php';
        parent::setUp();
    }

    public function test_if_teams_are_attached_twice_for_invite_and_add()
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

    public function test_if_team_invites_are_pending()
    {
        $userA = fsltCreateUser();
        $userB = fsltCreateUser();

        $this->be($userA);
        $team = fsltCreateTeam();
        $team->invite($userB);

        $this->assertTrue($team->invitedUsers->contains($userB));
        $this->assertFalse($team->invitedUsers->contains($userA));
    }

    public function test_if_a_user_can_belong_to_multiple_teams()
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

    public function test_if_a_user_can_be_removed_from_a_team()
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

    public function test_if_a_user_can_retrieve_owned_teams()
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

    public function test_if_a_user_can_get_a_specific_team_invite()
    {
        $userA = fsltCreateUser();
        $userB = fsltCreateUser();

        $this->be($userA);
        $team = fsltCreateTeam();
        $team->invite($userB);
        $invite = $userB->invite($team);
        $this->assertEquals($invite->user_id, $userB->id);
        $this->assertEquals($invite->team_id, $team->id);
    }

    /** @test */
    public function query_for_users_not_in_team()
    {
        $teamA = fsltCreateTeam();
        $teamB = fsltCreateTeam();

        $userA = fsltCreateUser(['name' => 'Owner of team A and B']);
        $userB = fsltCreateUser(['name' => 'Not in any team']);
        $userC = fsltCreateUser(['name' => 'In Team A']);
        $userD = fsltCreateUser(['name' => 'In Team B']);
        $userE = fsltCreateUser(['name' => 'Invited Team A']);
        $userF = fsltCreateUser(['name' => 'Invited Team B']);

        $userC->addToTeam($teamA);
        $userD->addToTeam($teamB);
        $userE->inviteToTeam($teamA);
        $userF->inviteToTeam($teamB);
        $this->assertEquals(4, User::notInTeam($teamA)->count());
        $this->assertEquals(4, User::notInTeam($teamB)->count());
    }
}
