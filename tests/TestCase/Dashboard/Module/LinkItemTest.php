<?php
declare(strict_types=1);

namespace CrudView\Test\TestCase\Dashboard;

use Cake\TestSuite\TestCase;
use CrudView\Dashboard\Module\LinkItem;
use InvalidArgumentException;

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

    public function testInvalidTitle()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing title for LinkItem action');

        new LinkItem('', null);
    }

    public function testInvalidNullUrl()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid url specified for LinkItem');

        new LinkItem('Title', null);
    }

    public function testInvalidEmptyUrl()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid url specified for LinkItem');

        new LinkItem('Title', '');
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
