<?php
use Tests\TestCase;
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

    public function test_if_a_user_can_create_a_new_team ()
    {
        $user = fsltCreateUser();
        $this->be($user);
        $response = $this->post('/teams', ['name' => 'Sally']);
        $this->assertDatabaseHas('teams', ['name' => 'Sally', 'owner_id' => $user->id]);
        $this->assertDatabaseHas('team_user', ['user_id' => $user->id]);
    }

    public function test_if_a_user_can_be_invited_to_a_team ()
    {
        $user = fsltCreateUserWithTeam();
    }

    public function test_if_a_user_can_belong_to_multiple_teams ()
    {
        
    }
}