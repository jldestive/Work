<?php

namespace Tests\Feature;

use App\Models\RequestUser;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkUser;
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

    public function test_can_not_update_two_or_more_users_same_work(){
        $work = Work::factory()->create();
        $user = User::factory()->create();

        $workData = [
            'description' => 'This is a test work 2',
            'status' => 'Closed'
        ];

        $response = $this->actingAs($user)
            ->putJson('api/works/' . $work->id, $workData);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_can_delete_work(){
        $work = Work::factory()->create();
        $user = User::find($work->user_id);
        $userTemp = User::factory()->create();

        $userTemp->requests()->attach($work->id);
        $userTemp->userWorks()->attach($work->id);

        $response = $this->actingAs(User::find($work->id))
            ->deleteJson('api/works/' . $work->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertModelMissing($work);
        $this->assertDatabaseMissing(RequestUser::class, [
            'work_id' => $work->id
        ]);
        $this->assertDatabaseMissing(WorkUser::class, [
            'work_id' => $work->id
        ]);
    }

    public function test_can_not_find_work(){
        $work = Work::factory()->create();

        $response = $this->actingAs(User::find($work->user_id))
            ->getJson('api/works/' . 0);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_can_find_work(){
        $work = Work::factory()->create();

        $response = $this->actingAs(User::find($work->user_id))
            ->getJson('api/works/' . $work->id);

        $response->assertStatus(Response::HTTP_OK);
    }
}
