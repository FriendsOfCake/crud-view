<?php

declare(strict_types=1);

use Cake\Core\Plugin;

return [
    'CrudView' => [
        'siteTitle' => 'Crud View',
        'css' => [
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.css',
            'https://cdn.jsdelivr.net/npm/flatpickr@4.6.3/dist/flatpickr.min.css',
            'https://cdn.jsdelivr.net/npm/select2@4.0/dist/css/select2.min.css',
            'https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.2/dist/select2-bootstrap4.css',
            'CrudView.local',
        ],
        'js' => [
            'headjs' => [
                'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js',
                'https://cdn.jsdelivr.net/npm/flatpickr@4.6',
                'https://cdn.jsdelivr.net/npm/select2@4.0',
                'https://cdn.jsdelivr.net/jquery.dirtyforms/1.2.2/jquery.dirtyforms.min.js',
            ],
            'script' => [
                'CrudView.local',
            ],
        ],
        'datetimePicker' => false,
        'useAssetCompress' => Plugin::isLoaded('AssetCompress'),
        'tablesBlacklist' => [
            'phinxlog',
        ],
    ],
];
