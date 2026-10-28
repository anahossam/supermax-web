<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Creates the single admin account if none exists. The password comes from SEED_ADMIN_PASSWORD,
 * or a random one is generated and printed once.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (User::where('role', Role::Admin)->exists()) {
            return;
        }

        $password = env('SEED_ADMIN_PASSWORD') ?: Str::password(14, symbols: false);

        User::create([
            'name' => 'مدير النظام',
            'username' => env('SEED_ADMIN_USERNAME', 'admin'),
            'role' => Role::Admin,
            'is_active' => true,
            'password' => $password,
        ]);

        if (! env('SEED_ADMIN_PASSWORD')) {
            $this->command?->warn("Admin password (shown once, change it after first login): {$password}");
        }
    }
}
