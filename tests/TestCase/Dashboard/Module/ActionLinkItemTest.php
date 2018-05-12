<?php
namespace CrudView\Test\TestCase\Dashboard;

use Cake\TestSuite\TestCase;
use CrudView\Dashboard\Module\ActionLinkItem;
use CrudView\Dashboard\Module\LinkItem;

/**
 * ActionLinkItemTest class
 */
class ActionLinkItemTest extends TestCase
{
    public function testConstruct()
    {
        $item = new ActionLinkItem('Title', 'https://google.com');

        $expected = 'Title';
        $this->assertEquals($expected, $item->get('title'));

        $expected = 'https://google.com';
        $this->assertEquals($expected, $item->get('url'));

        $expected = ['target' => '_blank'];
        $this->assertEquals($expected, $item->get('options'));

        $expected = [];
        $this->assertEquals($expected, $item->get('actions'));
    }

    public function testActions()
    {
        $item = new ActionLinkItem('Title', 'https://google.com');
        $expected = 0;
        $this->assertCount($expected, $item->get('actions'));

        $linkItem = new LinkItem('Add', ['controller' => 'Posts', 'action' => 'add']);
        $item = new ActionLinkItem('Posts', ['controller' => 'Posts'], [], [$linkItem]);

        $expected = [$linkItem];
        $this->assertEquals($expected, $item->get('actions'));

        $actions = $item->get('actions');
        $expected = ['class' => ['btn btn-default']];
        $this->assertEquals($expected, $actions[0]->get('options'));
    }
}
