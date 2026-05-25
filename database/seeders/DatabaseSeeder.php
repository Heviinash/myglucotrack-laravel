<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Only seed if System God doesn't already exist
        if (!User::where('role', 'System God')->exists()) {

            $tenant = Tenant::create([
                'tenant_name' => 'System',
            ]);

            User::create([
                'fullname'  => 'System God',
                'email'     => 'god@myglucotrack.com',
                'password'  => Hash::make('password123'),
                'role'      => 'System God',
                'status'    => 'Active',
                'tenant_id' => $tenant->id,
            ]);

            $this->command->info('System God created: god@myglucotrack.com / password123');
        } else {
            $this->command->info('System God already exists, skipping.');
        }
    }
}
