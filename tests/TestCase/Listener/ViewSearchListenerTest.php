<?php
declare(strict_types=1);

namespace CrudView\Test\TestCase\Listener;

use Cake\Controller\Controller;
use Cake\Http\ServerRequest;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use CrudView\Listener\ViewSearchListener;

/**
 * Test case for ViewSearchListener.
 */
class ViewSearchListenerTest extends TestCase
{
    protected $fixtures = ['plugin.CrudView.Blogs'];

    /**
     * @var \Cake\Controller\Controller;
     */
    protected $controller;

    /**
     * @var \CrudView\Listener\ViewSearchListener
     */
    protected $listener;

    public function setUp(): void
    {
        $routesBuilder = Router::createRouteBuilder('/');
        $routesBuilder->setRouteClass(DashedRoute::class);
        $routesBuilder->connect('/{controller}/{action}/*', [])
            ->setExtensions(['json']);

        $request = new ServerRequest([
            'url' => '/blogs/index',
            'params' => ['controller' => 'Blogs', 'action' => 'index', 'plugin' => null, '_ext' => null],
        ]);

        $this->controller = new Controller($request, null, 'Blogs');

        $this->listener = new ViewSearchListener($this->controller);

        Router::setRequest($request);
    }

    public function testFields()
    {
        $this->listener->setConfig(['fields' => ['category_id']]);

        $fields = $this->listener->fields();
        $expected = [
            'category_id' => [
                'required' => false,
                'type' => 'select',
                'value' => null,
                'class' => 'autocomplete',
                'data-url' => '/blogs/lookup.json?id=category_id&value=category_id',
            ],
        ];
        $this->assertEquals($expected, $fields);

        $this->listener->setConfig([
            'fields' => ['category_id' => ['data-url' => '/custom']],
        ], null, true);

        $fields = $this->listener->fields();
        $expected['category_id']['data-url'] = '/custom';
        $this->assertEquals($expected, $fields);
    }
}
