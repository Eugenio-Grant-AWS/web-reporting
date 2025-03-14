<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRole = config('roles.models.role')::where('name', '=', 'User')->first();
        $adminRole = config('roles.models.role')::where('name', '=', 'Admin')->first();
        $permissions = config('roles.models.permission')::all();

        /*
         * Add Users
         *
         */
        if (config('roles.models.defaultUser')::where('email', '=', 'admin@admin.com')->first() === null) {
            $adminUser = config('roles.models.defaultUser')::create([
                'firstname'     => 'Admin',
                'lastname'  => 'Test',
                'email'    => 'admin@admin.com',
                'password' => bcrypt('password'),
            ]);

            $adminUser->attachRole($adminRole);
            foreach ($permissions as $permission) {
                $adminUser->attachPermission($permission);
            }
        }

        if (config('roles.models.defaultUser')::where('email', '=', 'user@user.com')->first() === null) {
            $newUser = config('roles.models.defaultUser')::create([
                'firstname'     => 'User',
                'lastname' => 'Test',
                'email'    => 'user@user.com',
                'password' => bcrypt('password'),
            ]);

            $newUser->attachRole($userRole);
        }
    }
}
