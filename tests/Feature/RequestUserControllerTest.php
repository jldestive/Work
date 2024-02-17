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

class RequestUserControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_request_user_to_work(){
        $work = Work::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('api/request-user', [
            'work_id' => $work->id
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'id',
            'work_id',
            'user_id',
            'created_at',
            'updated_at',
            'status'
        ]);
    }

    public function test_can_not_request_user_to_work_for_the_same_user(){
        $work = Work::factory()->create();
        $user = User::find($work->user_id);

        $response = $this->actingAs($user)->postJson('api/request-user', [
            'work_id' => $work->id
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'message' => 'The same user can not requets this work'
        ]);
    }

    public function test_can_not_request_user_to_work_closed(){
        $work = Work::factory()->create();
        $work->status = 'Closed';
        $work->save();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('api/request-user', [
            'work_id' => $work->id
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'message' => 'This work is closed'
        ]);
    }

    public function test_can_not_request_user_to_work_where_are_working(){
        $work = Work::factory()->create();
        $user = User::factory()->create();

        $user->load('userWorks');
        $user->userWorks()->attach($work->id);

        $response = $this->actingAs($user)->postJson('api/request-user', [
            'work_id' => $work->id
        ]);
        $response->assertJson([
            'message' => 'The user is working in this work'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_can_approved_request_user(){
        $work = Work::factory()->create();
        $user = User::find($work->user_id);
        $otherUser = User::factory()->create();

        $requestUser = RequestUser::create([
            'user_id' => $otherUser->id,
            'work_id' => $work->id
        ]);

        $userWork = WorkUser::create([
            'user_id' => $otherUser->id,
            'work_id' => $work->id
        ]);

        $requestUser = RequestUser::create([
            'user_id' => $otherUser->id,
            'work_id' => $work->id
        ]);

        $response =  $this->actingAs($user)->putJson('api/request-user/' . $requestUser->id, [
            'status' => 'Approved'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $requestUser->id,
            'work_id' => $work->id,
            'user_id' => $otherUser->id,
            'status' => 'Approved'
        ]);
    }

    public function test_can_reject_request_user(){
        $work = Work::factory()->create();
        $user = User::find($work->user_id);
        $otherUser = User::factory()->create();

        $requestUser = RequestUser::create([
            'user_id' => $otherUser->id,
            'work_id' => $work->id
        ]);

        $response =  $this->actingAs($user)->putJson('api/request-user/' . $requestUser->id, [
            'status' => 'Reject'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $requestUser->id,
            'work_id' => $work->id,
            'user_id' => $otherUser->id,
            'status' => 'Reject'
        ]);
    }

    public function test_can_not_update_status_in_request_user(){
        $work = Work::factory()->create();
        $user = User::find($work->user_id);
        $otherUser = User::factory()->create();
        $requestUser = RequestUser::create([
            'user_id' => $otherUser->id,
            'work_id' => $work->id
        ]);
        sleep(1);

        $userWork = WorkUser::create([
            'user_id' => $otherUser->id,
            'work_id' => $work->id
        ]);

        $response = $this->actingAs($user)->putJson('api/request-user/'. $requestUser->id, [
            'status' => 'Approved'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'message' => 'This request can not modify because the user is working in that work'
        ]);
    }

    public function test_can_not_update_status_if_work_is_closed(){
        $work = Work::factory()->create();
        $user = User::find($work->user_id);
        $otherUser = User::factory()->create();
        $work->status = 'Closed';
        $work->save();

       $requestUser = RequestUser::create([
            'user_id' => $otherUser->id,
            'work_id' => $work->id
        ]);

        $response =  $this->actingAs($user)->putJson('api/request-user/' . $requestUser->id, [
            'status' => 'Approved'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'message' => 'This work is closed'
        ]);
    }

    public function test_can_not_update_status_for_other_user(){
        $work = Work::factory()->create();
        $otherUser = User::factory()->create();

       $requestUser = RequestUser::create([
            'user_id' => $otherUser->id,
            'work_id' => $work->id
        ]);

        $response =  $this->actingAs($otherUser)->putJson('api/request-user/' . $requestUser->id, [
            'status' => 'Approved'
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'You do not have permission to modify this information.'
        ]);
    }
}
