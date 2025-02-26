<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Role Types
         *
         */
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Admin Role',
            'level' => 5, // Admin role level
        ]);

        // Creating user role
        $userRole = Role::create([
            'name' => 'User',
            'slug' => 'user',
            'description' => 'User Role',
            'level' => 1, // Regular user role level
        ]);

        // Assign all permissions to the admin role
        $permissions = Permission::all(); // Get all permissions
        $adminRole->permissions()->sync($permissions); // Attach all permissions to the admin role

    }
}
