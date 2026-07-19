<?php

namespace Database\Seeders;

use App\Models\StationOfficer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StationOfficerSeeder extends Seeder
{
    public function run(): void
    {
        $officers = [
            ['station_code' => 'OPARO',                   'officer_name' => 'Zaldy A. Arenas',           'academic_suffix' => 'MDMG',      'position' => 'PARPO II'],
            ['station_code' => 'LTID',                    'officer_name' => 'Eldaliza R. Angcon',        'academic_suffix' => null,         'position' => 'CARPO'],
            ['station_code' => 'PBDD',                    'officer_name' => 'Precy S. Manla',            'academic_suffix' => null,         'position' => 'MARPO/ OIC CARPO'],
            ['station_code' => 'Administrative Division', 'officer_name' => 'Merlina T. Babatid',        'academic_suffix' => 'MExEd, MPA', 'position' => 'Chief Admin Officer'],
            ['station_code' => 'Legal Division',          'officer_name' => 'Maryrose J. Zulueta',       'academic_suffix' => null,         'position' => 'Attorney IV'],
            ['station_code' => 'DARMO-Compostela',        'officer_name' => 'Joseto Visaya',             'academic_suffix' => null,         'position' => 'MARPO'],
            ['station_code' => 'DARMO-Laak',              'officer_name' => 'Avelino O. Tocmo',          'academic_suffix' => null,         'position' => 'MARPO'],
            ['station_code' => 'DARMO-Mabini',            'officer_name' => 'William C. Abenales',       'academic_suffix' => null,         'position' => 'SARPO/OIC MARPO'],
            ['station_code' => 'DARMO-Maco',              'officer_name' => 'Brenda D. Manco',           'academic_suffix' => null,         'position' => 'MARPO'],
            ['station_code' => 'DARMO-Maragusan',         'officer_name' => 'Dandy B. Barulo',           'academic_suffix' => null,         'position' => 'MARPO'],
            ['station_code' => 'DARMO-Mawab',             'officer_name' => 'Anthony R. Fuerzas',        'academic_suffix' => null,         'position' => 'SARPT/ OIC MARPO'],
            ['station_code' => 'DARMO-Monkayo',           'officer_name' => 'Noreen Nicolas',            'academic_suffix' => null,         'position' => 'MARPO'],
            ['station_code' => 'DARMO-Montevista',        'officer_name' => 'Alexander C. Delima',       'academic_suffix' => null,         'position' => 'SARPT/ OIC MARPO'],
            ['station_code' => 'DARMO-Nabunturan',        'officer_name' => 'Precy S. Manla',            'academic_suffix' => null,         'position' => 'MARPO'],
            ['station_code' => 'DARMO-New Bataan',        'officer_name' => 'Ana A. Romanillos',         'academic_suffix' => null,         'position' => 'MARPO'],
            ['station_code' => 'DARMO-Pantukan',          'officer_name' => 'Allan V. Manuales',         'academic_suffix' => null,         'position' => 'MARPO'],
        ];

        foreach ($officers as $officer) {
            StationOfficer::updateOrCreate(
                ['station_code' => $officer['station_code']],
                $officer
            );

            [$firstName, $middleName, $lastName] = $this->parseNames($officer['officer_name']);
            $username = strtolower($firstName);

            // Ensure unique username by appending a suffix if taken
            $base = $username;
            $suffix = 1;
            while (User::where('username', $username)->exists()) {
                $username = $base . $suffix;
                $suffix++;
            }

            $user = User::updateOrCreate(
                ['username' => $username],
                [
                    'email' => "{$username}@example.com",
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_USER,
                    'email_verified_at' => now(),
                ]
            );

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'last_name' => $lastName,
                    'academic_suffix' => $officer['academic_suffix'] ?? null,
                    'position' => $officer['position'],
                    'area_of_assignment' => $officer['station_code'],
                ]
            );

            // Link station officer to user and sync name
            $stationOfficer = StationOfficer::where('station_code', $officer['station_code'])->first();
            if ($stationOfficer) {
                $constructedName = $firstName
                    . ($middleName ? ' ' . $middleName : '')
                    . ' ' . $lastName;

                $stationOfficer->update([
                    'user_id' => $user->id,
                    'officer_name' => $constructedName,
                    'academic_suffix' => $officer['academic_suffix'] ?? null,
                ]);
            }
        }
    }

    private function parseNames(string $officerName): array
    {
        $name = trim(explode(',', $officerName)[0]);
        $parts = preg_split('/\s+/', $name);

        $firstName = $parts[0] ?? $name;
        $lastName = $parts[count($parts) - 1] ?? $firstName;
        $middleName = count($parts) > 2
            ? implode(' ', array_slice($parts, 1, -1))
            : null;

        return [$firstName, $middleName, $lastName];
    }
}
