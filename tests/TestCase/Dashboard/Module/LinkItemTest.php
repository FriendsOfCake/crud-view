<?php
namespace CrudView\Test\TestCase\Dashboard;

use Cake\TestSuite\TestCase;
use CrudView\Dashboard\Module\LinkItem;

/**
 * LinkItemTest class
 */
class LinkItemTest extends TestCase
{
    public function testConstruct()
    {
        $item = new LinkItem('Title', 'https://google.com');

        $expected = 'Title';
        $this->assertEquals($expected, $item->get('title'));

        $expected = 'https://google.com';
        $this->assertEquals($expected, $item->get('url'));

        $expected = ['target' => '_blank'];
        $this->assertEquals($expected, $item->get('options'));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Missing title for LinkItem action
     */
    public function testInvalidTitle()
    {
        $item = new LinkItem('', null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid url specified for LinkItem
     */
    public function testInvalidNullUrl()
    {
        $item = new LinkItem('Title', null);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid url specified for LinkItem
     */
    public function testInvalidEmptyUrl()
    {
        $item = new LinkItem('Title', '');
    }

    public function testOptions()
    {
        $item = new LinkItem('Title', ['controller' => 'Posts']);
        $expected = [];
        $this->assertEquals($expected, $item->get('options'));

        $item = new LinkItem('Title', '/posts');
        $expected = [];
        $this->assertEquals($expected, $item->get('options'));

        $item = new LinkItem('Title', 'http://google.com');
        $expected = ['target' => '_blank'];
        $this->assertEquals($expected, $item->get('options'));

        $item = new LinkItem('Title', 'https://google.com');
        $expected = ['target' => '_blank'];
        $this->assertEquals($expected, $item->get('options'));
    }
}
