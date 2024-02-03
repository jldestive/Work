<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Work;
use App\Models\WorkUser;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WorkUserTest extends TestCase
{
    use DatabaseTransactions;

    public function test_work_user_can_be_created(){
        $user = User::factory()->create();

        $work = Work::factory()->create();
        $statudes = ['Working', 'Dismissed', 'Resignation'];

        $user->load('userWorks');
        $status = $statudes[array_rand($statudes)];
        $user->userWorks()->attach($work->id, ['status' => $status]);
        $workUser = WorkUser::where('user_id', $user->id)->where('work_id', $work->id)->first();

        $this->assertNotNull($workUser);
        $this->assertEquals($user->id, $workUser->user_id);
        $this->assertEquals($work->id, $workUser->work_id);
        $this->assertEquals($status, $workUser->status);
    }

    public function test_work_user_can_be_updated(){
        $user = User::factory()->create();

        $work = Work::factory()->create();
        $statudes = ['Working', 'Dismissed', 'Resignation'];

        $user->load('userWorks');
        $status = $statudes[array_rand($statudes)];
        $user->userWorks()->attach($work->id, ['status' => $status]);
        $status = $statudes[array_rand($statudes)];


        $workUser = WorkUser::where('user_id', $user->id)->where('work_id', $work->id)->update(['status' => $status]);
        $workUser = WorkUser::where('user_id', $user->id)->where('work_id', $work->id)->first();

        $this->assertNotNull($workUser);
        $this->assertEquals($user->id, $workUser->user_id);
        $this->assertEquals($work->id, $workUser->work_id);
        $this->assertEquals($status, $workUser->status);
    }

    public function test_work_user_can_be_deleted(){
        $user = User::factory()->create();

        $work = Work::factory()->create();
        $statudes = ['Working', 'Dismissed', 'Resignation'];

        $user->load('userWorks');
        $status = $statudes[array_rand($statudes)];
        $user->userWorks()->attach($work->id, ['status' => $status]);
        $user->userWorks()->detach($work->id);
        $workUser = WorkUser::where('user_id', $user->id)->where('work_id', $work->id)->first();

        $this->assertNull($workUser);
    }
}
