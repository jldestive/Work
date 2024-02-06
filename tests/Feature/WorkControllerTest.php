<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Work;
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

    public function test_can_update_work(){
        $user = User::factory()->create();

        $work = Work::create([
            'description' => 'This is a test work',
            'user_id' => $user->id
        ]);

        $workData = [
            'description' => 'This is a test work 2',
            'status' => 'Closed'
        ];

        $response = $this->actingAs($user)
            ->putJson('api/works/' . $work->id, $workData);

        $response->assertStatus(Response::HTTP_OK);

    }
}
