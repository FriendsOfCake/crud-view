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
    public function testConstruct(): void
    {
        $item = new LinkItem('Title', 'https://google.com');

        $expected = 'Title';
        $this->assertEquals($expected, $item->title);

        $expected = 'https://google.com';
        $this->assertEquals($expected, $item->url);

        $expected = ['target' => '_blank'];
        $this->assertEquals($expected, $item->options);
    }

    public function testInvalidEmptyUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid url specified for LinkItem');

        new LinkItem('Title', '');
    }

    public function testOptions(): void
    {
        $item = new LinkItem('Title', ['controller' => 'Posts']);
        $expected = [];
        $this->assertEquals($expected, $item->options);

        $item = new LinkItem('Title', '/posts');
        $expected = [];
        $this->assertEquals($expected, $item->options);

        $item = new LinkItem('Title', 'http://google.com');
        $expected = ['target' => '_blank'];
        $this->assertEquals($expected, $item->options);

        $item = new LinkItem('Title', 'https://google.com');
        $expected = ['target' => '_blank'];
        $this->assertEquals($expected, $item->options);
    }
}
