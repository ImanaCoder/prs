<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Replace with a secure password
        ]);
        // Create admin user
        $admin = User::create([
            'name' => 'Imana Coder',
            'email' => 'imanacoder@gmail.com',
            'password' => Hash::make('password'), // Replace with a secure password
        ]);
        $admin->assignRole('admin');

        // Create verifier user
        $verifier = User::create([
            'name' => 'Verifier User',
            'email' => 'verifier@example.com',
            'password' => Hash::make('password'), // Replace with a secure password
        ]);
        $verifier->assignRole('verifier');

        // Create sales manager user
        $salesManager = User::create([
            'name' => 'Sales Manager User',
            'email' => 'sales_manager@example.com',
            'password' => Hash::make('password'), // Replace with a secure password
        ]);
        $salesManager->assignRole('sales_manager');
    }
}
