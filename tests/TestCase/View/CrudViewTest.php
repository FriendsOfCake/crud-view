<?php
namespace CrudView\Test\TestCase\View;

use Cake\TestSuite\TestCase;
use CrudView\View\CrudView;

/**
 * ViewTest class
 */
class ViewTest extends TestCase
{
    public function testCreation()
    {
        $CrudView = new CrudView(
            null,
            null,
            null,
            [
                'helpers' => [
                    'CrudView' => [
                        'className' => 'CrudView.CrudView',
                        'fieldFormatters' => ['datetime' => 'formatTime']
                    ]
                ]
            ]
        );

        $expected = [
            'className' => 'CrudView.CrudView',
            'fieldFormatters' => ['datetime' => 'formatTime'],
        ];
        $result = $CrudView->CrudView->getConfig();
        $this->assertEquals($expected, $result);
    }
}
