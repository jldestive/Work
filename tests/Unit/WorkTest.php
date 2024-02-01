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
        $user = User::create([
            'name' => 'username',
            'email' => 'useremail@email.com',
            'password' => 'password'
        ]);

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
}
