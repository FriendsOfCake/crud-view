<?php
namespace CrudView\Test\TestCase\View\Widget;

use Cake\TestSuite\TestCase;
use Cake\View\Form\ContextInterface;
use Cake\View\StringTemplate;
use Cake\View\Widget\SelectBoxWidget;
use CrudView\View\Widget\DateTimeWidget;
use FriendsOfCake\TestUtilities\CompareTrait;

/**
 * DateTimeWidgetTest
 */
class DateTimeWidgetTest extends TestCase
{
    use CompareTrait;

    public function setUp()
    {
        parent::setUp();
        $this->initComparePath();
    }

    public function testRenderSimple()
    {
        $context = $this->getMockBuilder(ContextInterface::class)->getMock();
        $templates = new StringTemplate();
        $selectBox = new SelectBoxWidget($templates);
        $instance = new DateTimeWidget($templates, $selectBox);

        $result = $instance->render(['id' => 'the-id', 'name' => 'the-name', 'val' => '', 'type' => 'x', 'required' => false], $context);
        $this->assertHtmlSameAsFile('simple.html', $result);
    }
}
