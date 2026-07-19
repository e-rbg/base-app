<?php

namespace App\Console\Commands;

use App\Models\StationOfficer;
use Illuminate\Console\Command;

class SyncStationOfficerNames extends Command
{
    protected $signature = 'app:sync-station-officer-names';

    protected $description = 'Update station officer names to match their linked user profiles';

    public function handle(): void
    {
        $officers = StationOfficer::with('user.profile')
            ->whereNotNull('user_id')
            ->get();

        if ($officers->isEmpty()) {
            $this->info('No linked station officers to sync.');
            return;
        }

        $updated = 0;

        foreach ($officers as $officer) {
            $profile = $officer->user?->profile;

            if (!$profile) {
                $this->warn("{$officer->station_code}: linked user has no profile — skipped");
                continue;
            }

            $constructedName = $profile->first_name
                . ($profile->middle_name ? ' ' . $profile->middle_name : '')
                . ' ' . $profile->last_name;

            if ($officer->officer_name !== $constructedName) {
                $oldName = $officer->officer_name;
                $officer->update(['officer_name' => $constructedName]);

                $this->line("{$officer->station_code}: \"{$oldName}\" → \"{$constructedName}\"");
                $updated++;
            }
        }

        $this->info("Updated {$updated} of {$officers->count()} station officers.");
    }
}
