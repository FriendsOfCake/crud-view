<?php
use Cake\Core\Plugin;

return [
    'CrudView' => [
        'siteTitle' => 'Crud View',
        'css' => [
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.css',
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css',
            'CrudView.local',
        ],
        'js' => [
            'headjs' => [
                'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.js',
                'https://cdn.jsdelivr.net/jquery.dirtyforms/1.2.3/jquery.dirtyforms.min.js',
            ],
            'script' => [
                'CrudView.local'
            ],
        ],
        'timezoneAwareDateTimeWidget' => false,
        'useAssetCompress' => Plugin::isLoaded('AssetCompress'),
        'tablesBlacklist' => [
            'phinxlog',
        ],
    ]
];
