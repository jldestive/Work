<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_update_user(): void
    {
        $user = User::factory()->create();

        $userData = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com'
        ];

        $response = $this->actingAs($user)->putJson('api/user', $userData);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $user->id,
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com'
        ]);
    }

    public function test_cannot_update_user_with_existing_username(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $userData = [
            'name' => 'John Doe',
            'username' => $user2->username,
            'email' => 'johndoe@example.com'
        ];

        $response = $this->actingAs($user)->putJson('api/user', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['username']);
    }

    public function test_cannot_update_email_with_existing_username(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $userData = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => $user2->email
        ];

        $response = $this->actingAs($user)->putJson('api/user', $userData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_can_update_avatar(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->postJson('/api/user/avatar', [
            'image' => $file
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Avatar updated'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'avatar_path' => 'http://localhost/storage/avatar.jpg/' . $file->hashName()
        ]);
    }

    public function test_can_delete_avatar(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['avatar_path' => 'http://localhost/storage/avatar.jpg']);

        $response = $this->actingAs($user)->postJson('/api/user/avatar');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Avatar deleted'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'avatar_path' => null
        ]);
    }

    public function test_cannot_update_avatar_with_invalid_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 1024);

        $response = $this->actingAs($user)->postJson('/api/user/avatar', [
            'image' => $file
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['image']);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'avatar_path' => $file->hashName()
        ]);
    }
}
