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
    protected array $fixtures = ['plugin.CrudView.Blogs'];

    /**
     * @var \Cake\Controller\Controller;
     */
    protected Controller $controller;

    /**
     * @var \CrudView\Listener\ViewSearchListener
     */
    protected ViewSearchListener $listener;

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

        $this->controller = new Controller($request, 'Blogs');
        $this->controller->loadComponent('Crud.Crud');

        $this->listener = new ViewSearchListener($this->controller);

        Router::setRequest($request);
    }

    public function testFields()
    {
        $this->listener->setConfig(['fields' => [
            'name',
            'auto' => ['class' => 'autocomplete'],
            'is_active',
            'user_id',
            'custom_select' => ['empty' => false, 'type' => 'select'],
        ]]);

        $fields = $this->listener->fields();
        $expected = [
            'name' => [
                'required' => false,
                'type' => 'text',
                'value' => null,
                'placeholder' => 'Name',
            ],
            'auto' => [
                'class' => 'autocomplete',
                'required' => false,
                'type' => 'select',
                'value' => null,
                'data-input-type' => 'text',
                'data-tags' => 'true',
                'data-allow-clear' => 'true',
                'data-placeholder' => 'Auto',
                'empty' => 'Auto',
                'data-url' => '/blogs/lookup.json?id=auto&value=auto',
            ],
            'is_active' => [
                'required' => false,
                'type' => 'select',
                'value' => null,
                'options' => [1 => 'Yes', 0 => 'No'],
                'class' => 'no-select2',
                'empty' => 'Is Active',
            ],
            'user_id' => [
                'required' => false,
                'type' => 'select',
                'value' => null,
                'class' => 'autocomplete',
                'empty' => 'User',
                'data-placeholder' => 'User',
                'data-url' => '/blogs/lookup.json?id=user_id&value=user_id',
            ],
            'custom_select' => [
                'empty' => false,
                'type' => 'select',
                'required' => false,
                'value' => null,
                'class' => 'autocomplete',
                'data-placeholder' => 'Custom Select',
                'data-url' => '/blogs/lookup.json?id=custom_select&value=custom_select',
            ],
        ];
        $this->assertSame($expected, $fields);

        $this->listener->setConfig([
            'fields' => ['name' => ['data-url' => '/custom']],
        ], null, true);

        $fields = $this->listener->fields();
        $expected['name']['data-url'] = '/custom';
        $this->assertEquals($expected, $fields);

        $this->listener->setConfig([
            'autocomplete' => false,
            'fields' => [
                'user_id',
                'custom_select' => ['empty' => false, 'type' => 'select', 'class' => 'autocomplete'],
            ],
        ], merge: false);

        $fields = $this->listener->fields();
        $expected = [
            'user_id' => [
                'required' => false,
                'type' => 'select',
                'value' => null,
                'empty' => 'User',
                'data-placeholder' => 'User',
            ],
            'custom_select' => [
                'empty' => false,
                'type' => 'select',
                'class' => 'autocomplete',
                'required' => false,
                'value' => null,
                'data-placeholder' => 'Custom Select',
                'data-url' => '/blogs/lookup.json?id=custom_select&value=custom_select',
            ],
        ];
        $this->assertSame($expected, $fields);
    }
}
