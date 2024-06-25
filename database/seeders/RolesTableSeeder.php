<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['guard_name' => 'web', 'name' => 'admin']);
        Role::create(['guard_name' => 'web', 'name' => 'verifier']);
        Role::create(['guard_name' => 'web', 'name' => 'sales_manager']);
    }
}
