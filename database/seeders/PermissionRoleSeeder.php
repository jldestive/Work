<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-role',
            'assign-role',
            'delete-role',
            'ban-user',
            'unban-user',
            'delete-work',
        ];

        $adminRole = Role::create([
            'name' => 'admin'
        ]);
        $adminRole->load('permissions');

        foreach($permissions as $permission){
            $created_permission = Permission::create([
                'name' => $permission
            ]);

            $adminRole->permissions()->attach($created_permission);
        }

        $moderatorRole = Role::create([
            'name' => 'moderator'
        ]);

        $permissions =  [
            'delete-work',
            'ban-user',
            'unban-user',
        ];

        foreach($permissions as $permission){
            $moderatorRole->permissions()->attach(Permission::where('name', $permission)->first());
        }
    }
}
