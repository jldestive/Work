<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_register_user(): void{
        $response = $this->postJson(route('register'), [
            'name' => 'Test User',
            'email' => 'test_from_phpunit@test.com',
            'username' => 'test_from_phpunit',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'name',
            'email',
            'username',
            'created_at',
            'updated_at',
            'id'
        ]);

        $response->assertJson([
            'name' => 'Test User',
            'email' => 'test_from_phpunit@test.com',
            'username' => 'test_from_phpunit',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test_from_phpunit@test.com',
            'username' => 'test_from_phpunit',
        ]);
    }
}
