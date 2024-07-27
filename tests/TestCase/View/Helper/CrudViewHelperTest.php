<?php
declare(strict_types=1);

namespace CrudView\Test\TestCase\View\Helper;

use Cake\I18n\DateTime;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use CrudView\View\Helper\CrudViewHelper;

/**
 * CrudViewHelperTest class
 */
class CrudViewHelperTest extends TestCase
{
    protected CrudViewHelper $CrudView;

    /**
     * @var \Cake\View\View&\PHPUnit\Framework\MockObject\MockObject
     */
    protected $View;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->View = new View(null, null, null, [
            'helpers' => [
                'Html' => ['className' => 'BootstrapUI.Html'],
            ],
        ]);

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
            ->willReturn(new Entity());

        $this->CrudView
            ->expects($this->any())
            ->method('columnType')
            ->with('created')
            ->willReturn('datetime');

        $value = new DateTime();
        $result = $this->CrudView->introspect('created', $value);
        $this->assertEquals('just now', $result);

        $this->CrudView->setConfig('fieldFormatters', [
            'datetime' => 'formatTime',
        ]);
        $result = $this->CrudView->introspect('created', $value);
        $this->assertEquals($this->CrudView->Time->format($value, 'KK:mm:ss a'), $result);

        $result = $this->CrudView->introspect('created', 'invalid');
        $this->assertEquals('<span class="bg-info badge">N/A</span>', $result);

        $result = $this->CrudView->introspect('created', null);
        $this->assertEquals('<span class="bg-info badge">N/A</span>', $result);

        $this->CrudView->setConfig('fieldFormatters', [
            'datetime' => function () {
                return 'formatted time';
            },
        ]);
        $result = $this->CrudView->introspect('created', $value);
        $this->assertEquals('formatted time', $result);
    }
}
