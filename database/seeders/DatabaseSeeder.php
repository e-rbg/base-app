<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PsgcSeeder::class,
            StationOfficerSeeder::class,
        ]);

        $superAdmin = User::create([
            'username' => 'elvonroy',
            'email' => 'elvonroy@example.com',
            'password' => Hash::make('12345678'),
            'role' => User::ROLE_SUPER_ADMIN,
            'email_verified_at' => now(),
        ]);

        $superAdmin->profile()->create([
            'first_name' => 'Elvon',
            'middle_name' => 'Roy',
            'last_name' => 'Gervacio',
        ]);

        User::factory()->create([
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);

        $this->call([
            TravelOrderSeeder::class,
        ]);
    }
}
