<?php

namespace App\Functional\Api\V1\Controllers;

use DB;
use Config;
use App\User;
use App\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ResetPasswordControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $user = new User([
            'name' => 'Daniel',
            'email' => 'danielrodriguesdrs331@gmail.com',
            'password' => '123'
        ]);

        $user->save();

        DB::table('password_resets')->insert([
            'email' => 'danielrodriguesdrs331@gmail.com',
            'token' => bcrypt('meutokenaqui'),
            'created_at' => Carbon::now()
        ]);
    }

    public function testResetSuccessfully()
    {
        $this->post('api/auth/reset', [
            'email' => 'danielrodriguesdrs331@gmail.com',
            'token' => 'meutokenaqui',
            'password' => '123',
            'password_confirmation' => '123'
        ])->assertJson([
            'status' => 'ok'
        ])->isOk();
    }

    public function testResetSuccessfullyWithTokenRelease()
    {
        Config::set('boilerplate.reset_password.release_token', true);

        $this->post('api/auth/reset', [
            'email' => 'danielrodriguesdrs331@gmail.com',
            'token' => 'meutokenaqui',
            'password' => '123',
            'password_confirmation' => '123'
        ])->assertJsonStructure([
            'status',
            'token'
        ])->assertJson([
            'status' => 'ok'
        ])->isOk();
    }

    public function testResetReturnsProcessError()
    {
        $this->post('api/auth/reset', [
            'email' => 'danielrodriguesdrs331@gmail.com',
            'token' => 'meutokeninvalidoaqui',
            'password' => '123',
            'password_confirmation' => '123'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(500);
    }

    public function testResetReturnsValidationError()
    {
        $this->post('api/auth/reset', [
            'email' => 'danielrodriguesdrs331@gmail.com',
            'token' => 'meutokenaqui',
            'password' => '123'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(422);
    }
}
