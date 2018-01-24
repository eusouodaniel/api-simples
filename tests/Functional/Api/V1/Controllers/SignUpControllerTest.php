<?php

namespace App\Functional\Api\V1\Controllers;

use Config;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SignUpControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testSignUpSuccessfully()
    {
        $this->post('api/auth/signup', [
            'name' => 'Daniel',
            'email' => 'danielrodriguesdrs331@gmail.com',
            'password' => '123'
        ])->assertJson([
            'status' => 'ok'
        ])->assertStatus(201);
    }

    public function testSignUpSuccessfullyWithTokenRelease()
    {
        Config::set('boilerplate.sign_up.release_token', true);

        $this->post('api/auth/signup', [
            'name' => 'Daniel',
            'email' => 'danielrodriguesdrs331@gmail.com',
            'password' => '123'
        ])->assertJsonStructure([
            'status', 'token'
        ])->assertJson([
            'status' => 'ok'
        ])->assertStatus(201);
    }

    public function testSignUpReturnsValidationError()
    {
        $this->post('api/auth/signup', [
            'name' => 'Daniel',
            'email' => 'danielrodriguesdrs331@gmail.com'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(422);
    }
}
