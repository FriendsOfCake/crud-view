<?php
declare(strict_types=1);

namespace CrudView\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class BlogsFixture extends TestFixture
{
    public array $records = [
        ['name' => '1st post', 'body' => '1st post body', 'user_id' => 1, 'created' => '2024-09-03 00:00:00'],
        ['name' => '2nd post', 'body' => '2nd post body', 'user_id' => 1, 'created' => '2024-09-04 00:00:00'],
        ['name' => '3rd post', 'body' => '3rd post body', 'user_id' => 1, 'created' => '2024-09-05 00:00:00'],
        ['name' => '4th post', 'body' => '4th post body', 'user_id' => 1, 'created' => '2024-09-06 00:00:00'],
        ['name' => '5th post', 'body' => '5th post body', 'user_id' => 1, 'created' => '2024-09-07 00:00:00'],
    ];
}
