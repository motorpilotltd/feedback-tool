<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // define('STATUS_ALL', 'all');
        // define('STATUS_NEW', 'new');
        // define('STATUS_CONSIDERED', 'considered');
        // define('STATUS_DECLINED', 'declined');
        // define('STATUS_PLANNED', 'planned');
        // define('STATUS_STARTED', 'started');
        // define('STATUS_COMPLETED', 'completed');
        // define('STATUS_SUPPORTCALL', 'supportcall');

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
        // Truncate first

        Status::truncate();

        foreach ($statuses as $status) {
            Status::factory()->create([
                'name' => $status[1],
                'slug' => $status[0],
                'color' => $status[2],
            ]);
        }
    }
}
