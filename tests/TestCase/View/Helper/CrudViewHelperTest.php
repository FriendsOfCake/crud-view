<?php

declare(strict_types=1);

namespace CrudView\Test\TestCase\View\Helper;

use Cake\I18n\FrozenTime;
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
     * @var \Cake\View\Helper\CrudViewHelper
     */
    public $CrudView;

    /**
     * Mocked view
     *
     * @var \Cake\View\View|\PHPUnit_Framework_MockObject_MockObject
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
            ->setMethods()
            ->getMock();

        static::setAppNamespace();
    }

    public function testIntrospect()
    {
        $this->CrudView = $this->getMockBuilder(CrudViewHelper::class)
            ->setConstructorArgs([$this->View])
            ->setMethods(['columnType'])
            ->getMock();

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
        $this->assertEquals($this->CrudView->Time->nice($value), $result);

        $this->CrudView->setConfig('fieldFormatters', [
            'datetime' => function () {
                return 'formatted time';
            },
        ]);
        $result = $this->CrudView->introspect('created', $value);
        $this->assertEquals('formatted time', $result);
    }
}
