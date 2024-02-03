<?php

namespace Tests\Unit;

use App\Models\RequestUser;
use App\Models\User;
use App\Models\Work;
use App\Models\WorkUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RequestUserTest extends TestCase
{
    use DatabaseTransactions;

    public function test_request_user_can_be_created(){
        $user = User::factory()->create();

        $work = Work::factory()->create();
        $statudes = ['Approved', 'Pending', 'Reject'];

        $user->load('requests');
        $status = $statudes[array_rand($statudes)];
        $user->requests()->attach($work->id, ['status' => $status]);
        $requestUser = RequestUser::where('user_id', $user->id)->where('work_id', $work->id)->first();

        $this->assertNotNull($requestUser);
        $this->assertEquals($user->id, $requestUser->user_id);
        $this->assertEquals($work->id, $requestUser->work_id);
        $this->assertEquals($status, $requestUser->status);
    }

    public function test_request_user_can_be_updated(){
        $user = User::factory()->create();

        $work = Work::factory()->create();
        $statudes = ['Pending', 'Approved', 'Reject'];

        $user->load('requests');
        $status = $statudes[array_rand($statudes)];
        $user->requests()->attach($work->id, ['status' => $status]);
        $status = $statudes[array_rand($statudes)];


        $workUser = RequestUser::where('user_id', $user->id)->where('work_id', $work->id)->update(['status' => $status]);
        $workUser = RequestUser::where('user_id', $user->id)->where('work_id', $work->id)->first();

        $this->assertNotNull($workUser);
        $this->assertEquals($user->id, $workUser->user_id);
        $this->assertEquals($work->id, $workUser->work_id);
        $this->assertEquals($status, $workUser->status);
    }

    public function test_request_user_can_be_deleted(){
        $user = User::factory()->create();

        $work = Work::factory()->create();
        $statudes = ['Pending', 'Approved', 'Reject'];

        $user->load('requests');
        $status = $statudes[array_rand($statudes)];
        $user->requests()->attach($work->id, ['status' => $status]);
        $user->requests()->detach($work->id);
        $workUser = WorkUser::where('user_id', $user->id)->where('work_id', $work->id)->first();

        $this->assertNull($workUser);
    }
}
