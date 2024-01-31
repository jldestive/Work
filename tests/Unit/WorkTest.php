<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Work;
use PHPUnit\Framework\TestCase;

class WorkTest extends TestCase
{
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
        $this->assertEquals('My first work ', $work->description);
        $this->assertEquals('Open', $work->status);
        $this->assertEquals($user->id, $work->id);
    }
}
