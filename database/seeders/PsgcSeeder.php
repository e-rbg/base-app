<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PsgcSeeder extends Seeder
{
    private string $dataPath;

    public function run(): void
    {
        $this->dataPath = database_path('psgc/data');

        DB::statement('PRAGMA foreign_keys = OFF');

        $regionMap = $this->seedRegions();
        $provinceMap = $this->seedProvinces($regionMap);
        $cityMuniMap = $this->seedCityMunicipalities($provinceMap, $regionMap);
        $this->seedBarangays($cityMuniMap, $provinceMap, $regionMap);

        DB::statement('PRAGMA foreign_keys = ON');
    }

    private function readCsv(string $filename): array
    {
        $path = $this->dataPath . '/' . $filename;
        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle);
        $rows = [];

        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = array_combine($headers, $row);
        }

        fclose($handle);
        return $rows;
    }

    private function seedRegions(): array
    {
        $rows = $this->readCsv('regions.csv');
        $map = [];

        foreach ($rows as $row) {
            $id = DB::table('regions')->insertGetId([
                'code'       => $row['code'],
                'name'       => $row['name'],
                'short_name' => $row['short_name'] ?: null,
                'island_group' => $row['island_group'] ?: null,
                'status'     => $row['status'] ?? 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $map[$row['code']] = $id;
        }

        $this->command->info("Seeded {$this->count('regions')} regions");
        return $map;
    }

    private function seedProvinces(array $regionMap): array
    {
        $rows = $this->readCsv('provinces.csv');
        $map = [];

        foreach ($rows as $row) {
            $id = DB::table('provinces')->insertGetId([
                'code'       => $row['code'],
                'name'       => $row['name'],
                'region_id'  => $regionMap[$row['region_code']] ?? null,
                'old_name'   => $row['old_name'] ?: null,
                'status'     => $row['status'] ?? 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $map[$row['code']] = $id;
        }

        $this->command->info("Seeded {$this->count('provinces')} provinces");
        return $map;
    }

    private function seedCityMunicipalities(array $provinceMap, array $regionMap): array
    {
        $rows = $this->readCsv('city_municipalities.csv');
        $map = [];

        foreach ($rows as $row) {
            $id = DB::table('city_municipalities')->insertGetId([
                'code'        => $row['code'],
                'name'        => $row['name'],
                'province_id' => $provinceMap[$row['province_code']] ?? null,
                'region_id'   => $regionMap[$row['region_code']] ?? null,
                'type'        => $row['type'],
                'income_class' => $row['income_class'] ?: null,
                'urban_rural'  => $row['urban_rural'] ?: null,
                'old_name'     => $row['old_name'] ?: null,
                'status'       => $row['status'] ?? 'active',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
            $map[$row['code']] = $id;
        }

        $this->command->info("Seeded {$this->count('city_municipalities')} city/municipalities");
        return $map;
    }

    private function seedBarangays(array $cityMuniMap, array $provinceMap, array $regionMap): void
    {
        $rows = $this->readCsv('barangays.csv');
        $batch = [];
        $batchSize = 500;

        foreach ($rows as $i => $row) {
            $batch[] = [
                'code'                   => $row['code'],
                'name'                   => $row['name'],
                'city_municipality_id'   => $cityMuniMap[$row['city_municipality_code']] ?? null,
                'province_id'            => $provinceMap[$row['province_code']] ?? null,
                'region_id'              => $regionMap[$row['region_code']] ?? null,
                'old_name'               => $row['old_name'] ?: null,
                'status'                 => $row['status'] ?? 'active',
                'created_at'             => now(),
                'updated_at'             => now(),
            ];

            if (count($batch) >= $batchSize) {
                DB::table('barangays')->insert($batch);
                $batch = [];
            }
        }

        if ($batch) {
            DB::table('barangays')->insert($batch);
        }

        $this->command->info("Seeded {$this->count('barangays')} barangays");
    }

    private function count(string $table): int
    {
        return DB::table($table)->count();
    }
}
