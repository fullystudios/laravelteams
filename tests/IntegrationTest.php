<?php
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Database\Eloquent\Model;
use Beestreams\LaravelImageable\Models\Image;
use Beestreams\LaravelImageable\Traits\Imageable;
use Beestreams\LaravelImageable\Helpers\ImageResizer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Beestreams\LaravelImageable\Jobs\ResizeImage;

class IntegrationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        //  ../../../vendor/bin/phpunit
        require './vendor/autoload.php';
        parent::setUp();
    }

    public function test_a_user_can_create_a_new_team ()
    {
        
    }

    /** @test */
    public function a_user_can_be_added_to_a_team ()
    {

    }
}