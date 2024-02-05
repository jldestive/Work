<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use DatabaseTransactions;

    public function test_role_can_be_created(){
        $role = Role::create([
            'name' => 'Test name',
        ]);

        $this->assertNotNull($role);
        $this->assertEquals('Test name', $role->name);
    }

    public function test_role_can_be_updated(){
        $role = Role::create([
            'name' => 'user'
        ]);

        $role->update([
            'name' => 'admin'
        ]);

        $this->assertNotNull($role);
        $this->assertEquals('admin', $role->name);
    }

    public function test_role_can_be_deleted(){
        $role = Role::UpdateOrCreate([
            'name' => 'user'
        ]);

        $role->delete();
        $role = Role::where('name', $role->name);

        $this->assertNotNull($role);
    }
}
