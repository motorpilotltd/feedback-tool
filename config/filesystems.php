<?php

return [

    'disks' => [
        'attachments' => [
            'driver' => 'local',
            'root' => storage_path('app/attachments'),
            'visibility' => 'private', // Set visibility to private to prevent direct access
        ],
    ],

];
