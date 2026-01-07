<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        Permission::create(['name' => 'view role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'view permission']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'update permission']);
        Permission::create(['name' => 'delete permission']);

        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'view archived user']);
        Permission::create(['name' => 'create archived user']);
        Permission::create(['name' => 'update archived user']);
        Permission::create(['name' => 'delete archived user']);

        Permission::create(['name' => 'view setting']);
        Permission::create(['name' => 'create setting']);
        Permission::create(['name' => 'update setting']);
        Permission::create(['name' => 'delete setting']);

        Permission::create(['name' => 'view company']);
        Permission::create(['name' => 'create company']);
        Permission::create(['name' => 'update company']);
        Permission::create(['name' => 'delete company']);

        Permission::create(['name' => 'view ticket type']);
        Permission::create(['name' => 'create ticket type']);
        Permission::create(['name' => 'update ticket type']);
        Permission::create(['name' => 'delete ticket type']);

        Permission::create(['name' => 'view ticket']);
        Permission::create(['name' => 'create ticket']);
        Permission::create(['name' => 'update ticket']);
        Permission::create(['name' => 'delete ticket']);

        Permission::create(['name' => 'view ticket comment']);
        Permission::create(['name' => 'create ticket comment']);
        Permission::create(['name' => 'update ticket comment']);
        Permission::create(['name' => 'delete ticket comment']);

        // Create Roles
        $superAdminRole = Role::create(['name' => 'super-admin']); //as super-admin
        $developerRole = Role::create(['name' => 'developer']);
        $userRole = Role::create(['name' => 'user']);

        // give all permissions to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);
        $developerRole->givePermissionTo($allPermissionNames);


        // Create User and assign Role to it.

        $superAdminUser = User::firstOrCreate([
                    'email' => 'superadmin@gmail.com',
                ], [
                    'name' => 'Super Admin',
                    'email' => 'superadmin@gmail.com',
                    'username' => 'superadmin',
                    'password' => Hash::make ('superadmin@gmail.com'),
                    'email_verified_at' => now(),
                ]);

        $superAdminUser->assignRole($superAdminRole);

        $superAdminProfile = $superAdminUser->profile()->firstOrCreate([
            'user_id' => $superAdminUser->id,
        ], [
            'user_id' => $superAdminUser->id,
            'first_name' => $superAdminUser->name,
        ]);

        $developerUser = User::firstOrCreate([
                            'email' => 'rauf@gmail.com'
                        ], [
                            'name' => 'Abdul Rauf',
                            'username' => 'abrauf',
                            'email' => 'rauf@gmail.com',
                            'password' => Hash::make ('abrauf123'),
                            'email_verified_at' => now(),
                        ]);

        $developerUser->assignRole($developerRole);
        $developerUserProfile = $developerUser->profile()->firstOrCreate([
            'user_id' => $developerUser->id,
        ], [
            'user_id' => $developerUser->id,
            'first_name' => $developerUser->name,
        ]);
    }
}
