<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Work;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WorkTest extends TestCase
{
    use DatabaseTransactions; # This trait will rollback any changes to the database after each test (always use it)

    public function test_work_can_be_created(): void
    {
        $user = User::factory()->create();

        $work = Work::create([
            'description' => 'My first work',
            'status' => 'Open',
            'user_id' => $user->id
        ]);

        $this->assertNotNull($work);
        $this->assertEquals('My first work', $work->description);
        $this->assertEquals('Open', $work->status);
        $this->assertEquals($user->id, $work->user_id);
    }

    public function test_work_can_be_updated(){

        $work = Work::factory()->create();
        $workL = $work;
        $statudes = ['Open', 'Closed'];
        $status = $statudes[array_rand($statudes)];

        $work->update([
            'status' => $status
        ]);

        $this->assertNotNull($work);
        $this->assertEquals($workL->description, $work->description);
        $this->assertEquals($status, $work->status);
        $this->assertEquals($workL->id, $work->id);
    }

    public function test_work_can_be_deleted(){
        $work = Work::factory()->create();
        $work->delete();

        $workN = Work::find($work->id);

        $this->assertNull($workN);
    }
}
