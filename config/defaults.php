<?php
declare(strict_types=1);

use Cake\Core\Plugin;

// The function `parse_ini_file` may be disabled
$assets = parse_ini_string(
    file_get_contents(dirname(__FILE__) . '/asset_compress.ini'),
    true,
    INI_SCANNER_RAW
);

// Fix the CrudView local.css file for use Html::css()
foreach ($assets['crudview.css']['files'] as $i => $file) {
    if ($file === 'plugin:CrudView:css/local.css') {
        $assets['crudview.css']['files'][$i] = 'CrudView.local';
        break;
    }
}

// Fix the CrudView local.css file for use Html::css()
foreach ($assets['crudview.js']['files'] as $i => $file) {
    if ($file === 'plugin:CrudView:js/local.js') {
        $assets['crudview.js']['files'][$i] = 'CrudView.local';
        break;
    }
}

return [
    'CrudView' => [
        'siteTitle' => 'Crud View',
        'css' => $assets['crudview.css']['files'],
        'js' => [
            'headjs' => $assets['crudview_head.js']['files'],
            'script' => $assets['crudview.js']['files'],
        ],
        'datetimePicker' => false,
        'useAssetCompress' => Plugin::isLoaded('AssetCompress'),
        'tablesBlacklist' => [
            'phinxlog',
        ],
    ],
];
