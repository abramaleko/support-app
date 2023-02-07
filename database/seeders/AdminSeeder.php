<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create a super admin user
        $user=User::create([
            'name' => 'Super Admin',
            'email' => 'admin@domain.com',
            'is_staff' => true,
            'password' => Hash::make('TicketHub@2023'),
        ]);

        //creates an admin role
        $adminRole = Role::create(['name' => 'Super-Admin']);

        //assings the role to the user
        $user->assignRole($adminRole);
    }
}
