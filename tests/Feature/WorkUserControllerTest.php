<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Work;
use App\Models\WorkUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class WorkUserControllerTest extends TestCase
{
    public function test_can_created_work_user(){
        $work = Work::factory()->create();
        $user = User::find($work->user_id);
        $otherUser = User::factory()->create();

        $otherUser->load('userWorks');
        $user->userWorks()->attach($work->id, ['status' => 'Finished']);

        $response = $this->actingAs($user)->postJson('api/work-user', [
            'work_id' => $work->id,
            'user_id' => $otherUser->id
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'user_id' => $otherUser->id,
            'work_id' => $work->id,
            'status' => 'Working',
        ]);
    }

    public function test_can_not_created_work_user_for_the_same_user(){
        $work = Work::factory()->create();
        $user = User::find($work->user_id);


        $response = $this->actingAs($user)->postJson('api/work-user', [
            'work_id' => $work->id,
            'user_id' => $user->id
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'message' => 'The user created this work'
        ]);
    }

    public function test_can_not_created_work_user_if_user_is_working(){
        $work = Work::factory()->create();
        $user = User::find($work->user_id);
        $otherUser = User::factory()->create();

        $otherUser->load('userWorks');
        $otherUser->userWorks()->attach($work->id);

        $response = $this->actingAs($user)->postJson('api/work-user', [
            'work_id' => $work->id,
            'user_id' => $otherUser->id
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'message' => 'The user is working in this work'
        ]);
    }

    public function test_can_not_created_work_user_if_work_is_closed(){
        $work = Work::factory()->create();
        $work->status = 'Closed';
        $work->save();

        $user = User::find($work->user_id);
        $otherUser = User::factory()->create();


        $response = $this->actingAs($user)->postJson('api/work-user', [
            'work_id' => $work->id,
            'user_id' => $otherUser->id
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'message' => 'This work is closed'
        ]);
    }

    public function test_can_update_status_work_user(){
        $work = Work::factory()->create();
        $user = User::find($work->user_id);
        $otherUser = User::factory()->create();

        $user->load('userWorks');
        $workUser = WorkUser::create([
            'work_id' => $work->id,
            'user_id' =>$otherUser->id
        ]);

        $response = $this->actingAs($user)->putJson('api/work-user/' . $workUser->id, [
            'status' => 'Finished'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'status' => 'Finished',
            'work_id' => $work->id,
            'user_id' => $otherUser->id
        ]);
    }

    public function test_can_not_update_status_work_user_if_work_is_closed(){
        $work = Work::factory()->create();
        $work->status = 'Closed';
        $work->save();
        $user = User::find($work->user_id);
        $otherUser = User::factory()->create();

        $otherUser->load('userWorks');
        $workUser = WorkUser::create([
            'work_id' => $work->id,
            'user_id' => $otherUser->id
        ]);

        $response = $this->actingAs($user)->putJson('api/work-user/' . $workUser->id, [
            'status' => 'Finished'
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'message' => 'This work is closed'
        ]);
    }

    public function test_can_not_update_status_work_user_other_user(){
        $work = Work::factory()->create();
        $user = User::factory()->create();

        $user->load('userWorks');
        $workUser = WorkUser::create([
            'work_id' => $work->id,
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->putJson('api/work-user/' . $workUser->id, [
            'status' => 'Finished'
        ]);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'You do not have permission to modify this information.'
        ]);
    }
}
