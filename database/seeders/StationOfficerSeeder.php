<?php

namespace Database\Seeders;

use App\Models\StationOfficer;
use Illuminate\Database\Seeder;

class StationOfficerSeeder extends Seeder
{
    public function run(): void
    {
        $officers = [
            ['station_code' => 'OPARO',                   'officer_name' => 'Zaldy A. Arenas, MDMG',                'position' => 'PARPO II'],
            ['station_code' => 'LTID',                    'officer_name' => 'Greg L. Clarin',                       'position' => 'Chief, LTID'],
            ['station_code' => 'PBDD',                    'officer_name' => 'Nancy S. Ramos',                       'position' => 'Chief, PBDD'],
            ['station_code' => 'Administrative Division', 'officer_name' => 'Merlina T. Babatid, MExEd, MPA',       'position' => 'Chief Admin Officer'],
            ['station_code' => 'Legal Division',          'officer_name' => 'Maryrose J. Zulueta',                  'position' => 'Attorney IV'],
            ['station_code' => 'DARMO-Compostela',        'officer_name' => 'Joseto Visaya',                        'position' => 'MARPO'],
            ['station_code' => 'DARMO-Laak',              'officer_name' => 'Avelino O. Tocmo',                     'position' => 'MARPO'],
            ['station_code' => 'DARMO-Mabini',            'officer_name' => 'Greg L. Clarin',                       'position' => 'MARPO'],
            ['station_code' => 'DARMO-Maco',              'officer_name' => 'Dandy B. Barulo',                      'position' => 'MARPO'],
            ['station_code' => 'DARMO-Maragusan',         'officer_name' => 'Eldaliza R. Angcon',                   'position' => 'MARPO'],
            ['station_code' => 'DARMO-Mawab',             'officer_name' => 'Anthony R. Fuerzas',                   'position' => 'OIC MARPO'],
            ['station_code' => 'DARMO-Monkayo',           'officer_name' => 'Noreen Nicolas',                       'position' => 'MARPO'],
            ['station_code' => 'DARMO-Montevista',        'officer_name' => 'Brenda D. Mangco',                     'position' => 'MARPO'],
            ['station_code' => 'DARMO-Nabunturan',        'officer_name' => 'Precy S. Manla',                       'position' => 'MARPO'],
            ['station_code' => 'DARMO-New Bataan',        'officer_name' => 'Ana A. Romanillos',                    'position' => 'MARPO'],
            ['station_code' => 'DARMO-Pantukan',          'officer_name' => 'Allan V. Manuales',                    'position' => 'MARPO'],
        ];

        foreach ($officers as $officer) {
            StationOfficer::updateOrCreate(
                ['station_code' => $officer['station_code']],
                $officer
            );
        }
    }
}
