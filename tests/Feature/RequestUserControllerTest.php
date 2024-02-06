<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Work;
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
        $user = User::find($work->id);

        $response = $this->postJson('users/request-work', [
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

    public function test_can_not_request_user_to_work(){
        $work = Work::factory()->create();
        $user = User::find($work->id);

        $response = $this->postJson('users/request-work', [
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
}
