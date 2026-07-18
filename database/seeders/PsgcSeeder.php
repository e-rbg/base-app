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
            $id = $this->upsert('regions', ['code' => $row['code']], [
                'name'        => $row['name'],
                'short_name'  => $row['short_name'] ?: null,
                'island_group' => $row['island_group'] ?: null,
                'status'      => $row['status'] ?? 'active',
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
            $id = $this->upsert('provinces', ['code' => $row['code']], [
                'name'       => $row['name'],
                'region_id'  => $regionMap[$row['region_code']] ?? null,
                'old_name'   => $row['old_name'] ?: null,
                'status'     => $row['status'] ?? 'active',
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
            $id = $this->upsert('city_municipalities', ['code' => $row['code']], [
                'name'          => $row['name'],
                'province_id'   => $provinceMap[$row['province_code']] ?? null,
                'region_id'     => $regionMap[$row['region_code']] ?? null,
                'type'          => $row['type'],
                'income_class'  => $row['income_class'] ?: null,
                'urban_rural'   => $row['urban_rural'] ?: null,
                'old_name'      => $row['old_name'] ?: null,
                'status'        => $row['status'] ?? 'active',
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

        foreach ($rows as $row) {
            $batch[] = [
                'code'                   => $row['code'],
                'name'                   => $row['name'],
                'city_municipality_id'   => $cityMuniMap[$row['city_municipality_code']] ?? null,
                'province_id'            => $provinceMap[$row['province_code']] ?? null,
                'region_id'              => $regionMap[$row['region_code']] ?? null,
                'old_name'               => $row['old_name'] ?: null,
                'status'                 => $row['status'] ?? 'active',
            ];

            if (count($batch) >= $batchSize) {
                $this->upsertBatch('barangays', $batch);
                $batch = [];
            }
        }

        if ($batch) {
            $this->upsertBatch('barangays', $batch);
        }

        $this->command->info("Seeded {$this->count('barangays')} barangays");
    }

    /**
     * Insert or update a row keyed on $unique, returning the record id.
     */
    private function upsert(string $table, array $unique, array $values): int
    {
        $existing = DB::table($table)->where($unique)->first();

        if ($existing) {
            DB::table($table)->where('id', $existing->id)->update(
                array_merge($values, ['updated_at' => now()])
            );
            return $existing->id;
        }

        return DB::table($table)->insertGetId(array_merge($unique, $values, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }

    /**
     * Batch insert-or-update keyed on the 'code' column.
     */
    private function upsertBatch(string $table, array $rows): void
    {
        $codes = array_column($rows, 'code');
        $existing = DB::table($table)->whereIn('code', $codes)->pluck('id', 'code');

        $toInsert = [];
        $toUpdate = [];

        foreach ($rows as $row) {
            if ($existing->has($row['code'])) {
                $toUpdate[$existing[$row['code']]] = $row;
            } else {
                $row['created_at'] = now();
                $toInsert[] = $row;
            }
        }

        if ($toInsert) {
            DB::table($table)->insert($toInsert);
        }

        foreach ($toUpdate as $id => $row) {
            DB::table($table)->where('id', $id)->update(
                array_merge($row, ['updated_at' => now()])
            );
        }
    }

    private function count(string $table): int
    {
        return DB::table($table)->count();
    }
}
