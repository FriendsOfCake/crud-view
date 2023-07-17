<?php
declare(strict_types=1);

namespace CrudView\Test\TestCase\Dashboard;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use CrudView\Dashboard\Dashboard;
use CrudView\View\Cell\DashboardTableCell;
use InvalidArgumentException;
use function Cake\I18n\__d;

/**
 * DashboardTest class
 */
class DashboardTest extends TestCase
{
    public function testConstruct()
    {
        $dashboard = new Dashboard();
        $expected = __d('CrudView', 'Dashboard');
        $this->assertEquals($expected, $dashboard->get('title'));

        $expected = [];
        $this->assertEquals($expected, $dashboard->get('children'));

        $expected = 1;
        $this->assertEquals($expected, $dashboard->get('columns'));

        $dashboard = new Dashboard('Test Title');
        $expected = 'Test Title';
        $this->assertEquals($expected, $dashboard->get('title'));
    }

    public function testInvalidConstruct()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Valid columns value must be one of [1, 2, 3, 4, 6, 12]');

        new Dashboard(null, 0);
    }

    public function testColumnChildren()
    {
        $dashboard = new Dashboard();
        $expected = [];
        $this->assertEquals($expected, $dashboard->getColumnChildren(1));

        $cell = new DashboardTableCell(new ServerRequest(), new Response());
        $return = $dashboard->addToColumn($cell);
        $this->assertEquals($dashboard, $return);
        $this->assertEquals([$cell], $dashboard->getColumnChildren(1));

        $expected = [];
        $this->assertEquals($expected, $dashboard->getColumnChildren(2));
    }
}
