<?php
declare(strict_types=1);

return [
    [
        'table' => 'blogs',
        'columns' => [
            'id' => ['type' => 'integer'],
            'is_active' => ['type' => 'boolean', 'default' => true, 'null' => false],
            'name' => ['type' => 'string', 'length' => 255, 'null' => false],
            'body' => ['type' => 'text', 'null' => false],
            'user_id' => ['type' => 'integer'],
            'created' => ['type' => 'datetime', 'null' => false],
        ],
        'constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ],
    [
        'table' => 'users',
        'columns' => [
            'id' => ['type' => 'integer'],
            'username' => ['type' => 'string', 'length' => 20, 'null' => false],
            'birth_date' => ['type' => 'date', 'null' => false],
        ],
        'constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ],
];
