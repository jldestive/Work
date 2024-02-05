<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class WorkControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_create_work(): void
    {
        $user = User::factory()->create();

        $workData = [
            'description' => 'This is a test work',
            'user_id' => $user->id
        ];

        $response =  $this->actingAs($user)
            ->postJson('api/works', $workData);

        $response->assertStatus(Response::HTTP_CREATED);
    }
}
