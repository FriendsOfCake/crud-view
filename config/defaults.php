<?php

return [
    'CrudView' => [
        'css' => [
            'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.css',
            'CrudView./eonasdan-bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
            'CrudView.local'
        ],
        'js' => [
            'headjs' => [
                'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js',
                'CrudView./moment/moment-with-locales.min.js',
                'CrudView./eonasdan-bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
            ],
            'script' => [
                'CrudView.local'
            ]
        ]
    ]
];
