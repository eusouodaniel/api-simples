<?php

namespace App\Functional\Api\V1\Controllers;

use Hash;
use App\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginControllerTest extends TestCase
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
    }

    public function testLoginSuccessfully()
    {
        $this->post('api/auth/login', [
            'email' => 'danielrodriguesdrs331@gmail.com',
            'password' => '123'
        ])->assertJson([
            'status' => 'ok'
        ])->assertJsonStructure([
            'status',
            'token'
        ])->isOk();
    }

    public function testLoginWithReturnsWrongCredentialsError()
    {
        $this->post('api/auth/login', [
            'email' => 'danielrodriguesdrs331@gmail.com',
            'password' => '123'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(403);
    }

    public function testLoginWithReturnsValidationError()
    {
        $this->post('api/auth/login', [
            'email' => 'danielrodriguesdrs331@gmail.com'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(422);
    }
}
