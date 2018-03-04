<?php
namespace CrudView\Test\TestCase\View\Widget;

use Cake\TestSuite\TestCase;
use Cake\View\Form\ContextInterface;
use Cake\View\StringTemplate;
use Cake\View\Widget\SelectBoxWidget;
use CrudView\View\Widget\DateTimeWidget;

/**
 * DateTimeWidgetTest
 */
class DateTimeWidgetTest extends TestCase
{
    public function testRenderSimple()
    {
        $context = $this->getMockBuilder(ContextInterface::class)->getMock();
        $templates = new StringTemplate();
        $selectBox = new SelectBoxWidget($templates);
        $instance = new DateTimeWidget($templates, $selectBox);

        $expected = [
            ['div' => true],
            'input' => [
                'type',
                'class',
                'value',
                'id',
                'role',
                'data-locale',
                'data-format',
            ],
            ['label' => true],
            'span' => [
                'class'
            ],
            '/label',
            '/div'
        ];
        $result = $instance->render(['id' => 'the-id', 'name' => 'the-name', 'val' => '', 'type' => 'x', 'required' => false], $context);
        $this->assertHtml($expected, $result);
    }
}
