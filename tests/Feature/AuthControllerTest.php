<?php

namespace Tests\Feature;

use App\Models\User;
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

    public function test_two_users_cannot_have_the_same_username(): void
    {
        $this->postJson(route('register'), [
            'name' => 'Test User',
            'email' => 'test1@test.com',
            'username' => 'test',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response = $this->postJson(route('register'), [
            'name' => 'Test User',
            'email' => 'test2@test.com',
            'username' => 'test',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_two_users_cannot_have_the_same_email(): void
    {
        $this->postJson(route('register'), [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'username' => 'test1',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response = $this->postJson(route('register'), [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'username' => 'test2',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_password_must_be_confirmed(): void
    {
        $response = $this->postJson(route('register'), [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'username' => 'test2',
            'password' => 'password',
            'password_confirmation' => 'other_password'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('password');
    }

    public function test_password_must_be_greater_than_8_characters(): void
    {
        $response = $this->postJson(route('register'), [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'username' => 'test2',
            'password' => '123',
            'password_confirmation' => '123'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('password');
    }

    public function test_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->postJson(route('login'), [
            'email' => 'test_from_phpunit@test.com',
            'password' => 'wrong_password'
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Invalid Credentials'
        ]);
    }
}
