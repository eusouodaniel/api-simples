<?php

namespace App\Functional\Api\V1\Controllers;

use App\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ForgotPasswordControllerTest extends TestCase
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

    public function testForgotPasswordRecoverySuccessfully()
    {
        $this->post('api/auth/recovery', [
            'email' => 'danielrodriguesdrs331@gmail.com'
        ])->assertJson([
            'status' => 'ok'
        ])->isOk();
    }

    public function testForgotPasswordRecoveryReturnsUserNotFoundError()
    {
        $this->post('api/auth/recovery', [
            'email' => 'danielrodriguesdrs331@gmail.com'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(404);
    }

    public function testForgotPasswordRecoveryReturnsValidationErrors()
    {
        $this->post('api/auth/recovery', [
            'email' => 'Não é email'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(422);
    }
}
