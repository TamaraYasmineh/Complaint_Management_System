<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
    
        if (!User::where('email', 'admin@example.com')->exists()) {
            $admin = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
            ]);

            $admin->assignRole('admin');
            $admin->createToken('AdminToken');
        }

        
        if (!User::where('email', 'employee@example.com')->exists()) {
            $employee = User::create([
                'name' => 'Default Employee',
                'email' => 'employee@example.com',
                'password' => Hash::make('emp12345'),
            ]);

            $employee->assignRole('employee');

            $employee->createToken('EmployeeToken');
        }

       
    }
}
