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

    public function test_if_authenticated_user_becomes_owner_of_new_team ()
    {
        $user = fsltCreateUser();
        $this->be($user);
        $team = fsltCreateTeam();
        $this->assertEquals($team->owner_id, $user->id);
    }

    public function test_if_authenticated_user_becomes_is_added_to_team ()
    {
        $user = fsltCreateUser();
        $this->be($user);
        $team = fsltCreateTeam();
        $this->assertTrue($team->members->contains($user));
    }

    public function test_if_a_user_is_added_to_team_when_accepting_invite ()
    {
        $user = fsltCreateUser();
        $this->be($user);
        $team = fsltCreateTeam();
        $user->inviteToTeam($team);
        $user->addToTeam($team);
        $this->assertDatabaseHas('team_invites', [
            'user_id' => $user->id,
            'team_id' => $team->id,
            'accepted_at' => Carbon::now()
        ]);
    }
}