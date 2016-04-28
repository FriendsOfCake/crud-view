<?php
use \Cake\Core\Plugin;

return [
    'CrudView' => [
        'brand' => 'Crud View',
        'css' => [
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.css',
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/css/selectize.bootstrap3.min.css',
            'CrudView.local'
        ],
        'js' => [
            'headjs' => [
                'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment-with-locales.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.js',
                'https://cdn.jsdelivr.net/jquery.dirtyforms/1.2.2/jquery.dirtyforms.min.js'
            ],
            'script' => [
                'CrudView.local'
            ]
        ],
        'timezoneAwareDateTimeWidget' => false,
        'useAssetCompress' => Plugin::loaded('AssetCompress')
    ]
];
