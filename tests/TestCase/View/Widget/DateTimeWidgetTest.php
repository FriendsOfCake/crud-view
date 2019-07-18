<?php
namespace CrudView\Test\TestCase\View\Widget;

use Cake\I18n\FrozenTime;
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

    public function setUp(): void
    {
        parent::setUp();
        $this->initComparePath();
    }

    /**
     * testRender
     *
     * @dataProvider renderProvider
     * @param string $compareFileName
     * @param array $data
     */
    public function testRenderSimple($compareFileName, $data)
    {
        $context = $this->getMockBuilder(ContextInterface::class)->getMock();
        $templates = new StringTemplate();
        $selectBox = new SelectBoxWidget($templates);
        $instance = new DateTimeWidget($templates, $selectBox);

        $result = $instance->render($data, $context);
        $this->assertHtmlSameAsFile($compareFileName, $result);
    }

    /**
     * Returns sets of:
     *  * file name to compare to
     *  * data for date time widget
     *
     * @return array
     */
    public function renderProvider()
    {
        return [
            [
                'simple.html',
                ['id' => 'the-id', 'name' => 'the-name', 'val' => '', 'type' => 'x', 'required' => false]
            ],
            [
                'with-string-value.html',
                ['id' => 'the-id', 'name' => 'the-name', 'val' => '2000-01-01', 'type' => 'x', 'required' => false]
            ],
            [
                'with-date-value.html',
                ['id' => 'the-id', 'name' => 'the-name', 'val' => (new FrozenTime(strtotime('2000-01-01'))), 'type' => 'x', 'required' => false]
            ]
        ];
    }
}
