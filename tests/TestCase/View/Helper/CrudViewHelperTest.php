<?php
declare(strict_types=1);

namespace CrudView\Test\TestCase\View\Helper;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use CrudView\View\Helper\CrudViewHelper;

/**
 * CrudViewHelperTest class
 */
class CrudViewHelperTest extends TestCase
{
    /**
     * Helper to be tested
     *
     * @var \Crud\View\Helper\CrudViewHelper
     */
    public $CrudView;

    /**
     * Mocked view
     *
     * @var \Cake\View\View&\PHPUnit_Framework_MockObject_MockObject
     */
    public $View;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->View = $this->getMockBuilder(View::class)
            ->onlyMethods([])
            ->getMock();

        static::setAppNamespace();
    }

    public function testIntrospect()
    {
        $this->CrudView = $this->getMockBuilder(CrudViewHelper::class)
            ->setConstructorArgs([$this->View])
            ->onlyMethods(['columnType', 'getContext'])
            ->getMock();

        $this->CrudView
            ->expects($this->any())
            ->method('getContext')
            ->will($this->returnValue(new Entity()));

        $this->CrudView
            ->expects($this->any())
            ->method('columnType')
            ->with('created')
            ->will($this->returnValue('datetime'));

        $value = new FrozenTime();
        $result = $this->CrudView->introspect('created', $value);
        $this->assertEquals('just now', $result);

        $this->CrudView->setConfig('fieldFormatters', [
            'datetime' => 'formatTime',
        ]);
        $result = $this->CrudView->introspect('created', $value);
        $this->assertEquals($this->CrudView->Time->format($value, 'KK:mm:ss a'), $result);

        $this->CrudView->setConfig('fieldFormatters', [
            'datetime' => function () {
                return 'formatted time';
            },
        ]);
        $result = $this->CrudView->introspect('created', $value);
        $this->assertEquals('formatted time', $result);
    }
}
