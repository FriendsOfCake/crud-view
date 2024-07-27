<?php
declare(strict_types=1);

namespace CrudView\Test\TestCase\View\Widget;

use Cake\Core\Configure;
use Cake\I18n\Date;
use Cake\TestSuite\TestCase;
use Cake\View\Form\ContextInterface;
use Cake\View\StringTemplate;
use Cake\View\Widget\SelectBoxWidget;
use CrudView\View\Widget\DateTimeWidget;
use FriendsOfCake\TestUtilities\CompareTrait;
use PHPUnit\Framework\Attributes\DataProvider;

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
        Configure::write('CrudView.datetimePicker', []);
    }

    /**
     * testRender
     *
     * @param string $compareFileName
     * @param array $data
     */
    #[DataProvider('renderProvider')]
    public function testRenderSimple($compareFileName, $data)
    {
        /** @var \Cake\View\Form\ContextInterface $context */
        $context = $this->getMockBuilder(ContextInterface::class)->getMock();
        $templates = new StringTemplate([
            'input' => '<input type="{{type}}" name="{{name}}"{{attrs}}/>',
        ]);
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
    public static function renderProvider()
    {
        return [
            [
                'simple.html',
                [
                    'id' => 'the-id',
                    'name' => 'the-name',
                    'val' => '',
                    'type' => 'date',
                    'required' => false,
                ],
            ],
            [
                'with-string-value.html',
                [
                    'id' => 'the-id2',
                    'name' => 'the-name2',
                    'val' => '2000-01-01',
                    'type' => 'date',
                    'required' => false,
                ],
            ],
            [
                'with-date-value.html',
                [
                    'id' => 'the-id3',
                    'name' => 'the-name3',
                    'val' => new Date('2000-01-01'),
                    'type' => 'date',
                    'required' => false,
                ],
            ],
            [
                'no-datetime-picker.html',
                [
                    'id' => 'the-id4',
                    'name' => 'the-name4',
                    'val' => '',
                    'type' => 'date',
                    'required' => false,
                    'datetimePicker' => false,
                ],
            ],
            [
                'no-wrap.html',
                [
                    'id' => 'the-id5',
                    'name' => 'the-name5',
                    'val' => '',
                    'type' => 'date',
                    'required' => false,
                    'datetimePicker' => ['data-wrap' => 'false'],
                ],
            ],
        ];
    }
}
