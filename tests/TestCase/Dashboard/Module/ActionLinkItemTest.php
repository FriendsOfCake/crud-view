<?php
declare(strict_types=1);

namespace CrudView\Test\TestCase\Dashboard;

use Cake\TestSuite\TestCase;
use CrudView\Dashboard\Module\ActionLinkItem;
use CrudView\Dashboard\Module\LinkItem;

/**
 * ActionLinkItemTest class
 */
class ActionLinkItemTest extends TestCase
{
    public function testConstruct(): void
    {
        $item = new ActionLinkItem('Title', 'https://google.com');

        $expected = 'Title';
        $this->assertEquals($expected, $item->title);

        $expected = 'https://google.com';
        $this->assertEquals($expected, $item->url);

        $expected = ['target' => '_blank'];
        $this->assertEquals($expected, $item->options);

        $expected = [];
        $this->assertEquals($expected, $item->actions);
    }

    public function testActions(): void
    {
        $item = new ActionLinkItem('Title', 'https://google.com');
        $expected = 0;
        $this->assertCount($expected, $item->actions);

        $linkItem = new LinkItem('Add', ['controller' => 'Posts', 'action' => 'add']);
        $item = new ActionLinkItem('Posts', ['controller' => 'Posts'], [], [$linkItem]);

        $expected = [$linkItem];
        $this->assertEquals($expected, $item->actions);

        $actions = $item->actions;
        $expected = ['class' => ['btn btn-secondary']];
        $this->assertEquals($expected, $actions[0]->options);
    }
}
