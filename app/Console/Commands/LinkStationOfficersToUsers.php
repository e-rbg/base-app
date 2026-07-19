<?php

namespace App\Console\Commands;

use App\Models\StationOfficer;
use App\Models\User;
use Illuminate\Console\Command;

class LinkStationOfficersToUsers extends Command
{
    protected $signature = 'app:link-station-officers-to-users';

    protected $description = 'Link existing StationOfficer records to Users by matching area_of_assignment';

    public function handle(): void
    {
        $officers = StationOfficer::whereNull('user_id')->get();

        if ($officers->isEmpty()) {
            $this->info('All station officers are already linked.');
            return;
        }

        $linked = 0;

        foreach ($officers as $officer) {
            $user = User::whereHas('profile', function ($q) use ($officer) {
                $q->where('area_of_assignment', $officer->station_code);
            })->first();

            if ($user) {
                $officer->update(['user_id' => $user->id]);
                $this->line("Linked: {$officer->station_code} -> {$user->fullName}");
                $linked++;
            } else {
                $this->warn("No user found for: {$officer->station_code} ({$officer->officer_name})");
            }
        }

        $this->info("Linked {$linked} of {$officers->count()} station officers.");
    }
}
