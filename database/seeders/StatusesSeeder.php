<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Keyed on the status slug via updateOrCreate, so the seeder is idempotent
     * and re-running it never re-numbers rows that ideas already reference.
     */
    public function run(): void
    {
        // Refer to WireUI badges section for color
        // https://livewire-wireui.com/docs/badges
        // slug, name, color
        $statuses = [
            [config('const.STATUS_NEW'), 'Awaiting Consideration', 'amber'],
            [config('const.STATUS_CONSIDERED'), 'Under Consideration', 'gray'],
            [config('const.STATUS_DECLINED'), 'Declined', 'red'],
            [config('const.STATUS_PLANNED'), 'In Planning', 'blue'],
            [config('const.STATUS_STARTED'), 'In Development', 'pink'],
            [config('const.STATUS_COMPLETED'), 'Completed', 'emerald'],
            [config('const.STATUS_SUPPORTCALL'), 'Redirected to Service Desk - CLOSED', 'rose'],
        ];

        foreach ($statuses as [$slug, $name, $color]) {
            Status::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'color' => $color],
            );
        }
    }
}
