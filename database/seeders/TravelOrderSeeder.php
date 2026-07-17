<?php

namespace Database\Seeders;

use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TravelOrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('username', 'elvonroy')->first();

        if (! $user) {
            $this->command->warn('Skipping TravelOrderSeeder: elvonroy user not found. Run DatabaseSeeder first.');
            return;
        }

        $barangays = DB::table('barangays')
            ->join('city_municipalities', 'barangays.city_municipality_id', '=', 'city_municipalities.id')
            ->join('provinces', 'city_municipalities.province_id', '=', 'provinces.id')
            ->where('city_municipalities.name', 'Mabini')
            ->where('provinces.name', 'Davao de Oro')
            ->pluck('barangays.name')
            ->toArray();

        $purposes = [
            'Conduct field inspection and farm monitoring',
            'Facilitate land acquisition beneficiary evaluation',
            'Deliver agrarian reform documents to beneficiaries',
            'Attend municipal agrarian reform coordination meeting',
            'Conduct ocular inspection of land reform areas',
            'Facilitate CARP beneficiary interview and profiling',
            'Distribute land titles to qualified beneficiaries',
            'Conduct community consultation on agrarian reform',
            'Verify land boundary and survey coordinates',
            'Provide technical assistance to farmer beneficiaries',
            'Attend DAR provincial technical conference',
            'Conduct post-distribution monitoring visit',
            'Facilitate dispute resolution among beneficiaries',
            'Inspect irrigation project site for beneficiaries',
            'Gather baseline data for agrarian reform impact assessment',
            'Coordinate with LGU on land use conversion concerns',
            'Conduct farmer field school coordination meeting',
            'Submit field report to PARO office',
            'Facilitate farmers organization capacity building',
            'Inspect farm-to-market road project beneficiary area',
        ];

        $statuses = ['pending', 'pending', 'pending', 'pending', 'pending'];
        $recommenderSigned = [false, false, true, true, false]; // 2 out of 5 have recommender signed

        $year = now()->format('Y');
        $month = now()->format('m');
        $counter = 1;

        foreach ($barangays as $barangay) {
            for ($i = 0; $i < 5; $i++) {
                $travelDate = now()->subDays(rand(0, 30));
                $departureDate = $travelDate->copy();
                $returnDate = $travelDate->copy()->addDay();
                $status = $statuses[array_rand($statuses)];
                $hasRecommenderSigned = $recommenderSigned[array_rand($recommenderSigned)];

                DB::table('travel_orders')->insert([
                    'id'                     => Str::uuid()->toString(),
                    'user_id'                => $user->id,
                    'travel_order_no'        => "DARDDO-TO-{$year}-{$month}-" . str_pad($counter, 3, '0', STR_PAD_LEFT),
                    'travel_type'            => 'intra_municipal',
                    'travel_date'            => $travelDate->toDateString(),
                    'name'                   => 'Elvon Roy Gervacio',
                    'position'               => 'Agrarian Reform Program Technologist',
                    'station'                => 'DARMO-Mabini',
                    'transportation_means'   => 'Land',
                    'vehicle_type'           => 'Government Vehicle',
                    'destination'            => $barangay,
                    'departure_date'         => $departureDate->toDateString(),
                    'return_date'            => $returnDate->toDateString(),
                    'report_to'              => 'Greg L. Clarin',
                    'purpose_of_trip'        => json_encode([$purposes[array_rand($purposes)]]),
                    'accommodation_type'     => 'Live-out',
                    'recommending_approval'  => 'Precy S. Manla',
                    'recommending_position'  => 'MARPO',
                    'approved_by_name'       => 'Greg L. Clarin',
                    'approved_by_position'   => 'MARPO',
                    'fund_custodian'         => 'Maria Siezamie B. Agoilo',
                    'status'                 => $status,
                    'approved_at'            => null,
                    'recommending_approved_at' => $hasRecommenderSigned ? $travelDate->toDateTimeString() : null,
                    'esignature_recommender_hash' => $hasRecommenderSigned ? md5('recommender' . $travelDate) : null,
                    'created_at'             => now(),
                    'updated_at'             => now(),
                ]);

                $counter++;
            }
        }

        $this->command->info("Seeded " . ($counter - 1) . " travel orders for elvonroy across " . count($barangays) . " barangays");
    }
}
