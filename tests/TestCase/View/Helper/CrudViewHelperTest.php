<?php
declare(strict_types=1);

namespace CrudView\Test\TestCase\View\Helper;

use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use CrudView\View\Helper\CrudViewHelper;

/**
 * CrudViewHelperTest class
 */
class CrudViewHelperTest extends TestCase
{
    protected array $fixtures = ['plugin.CrudView.Blogs', 'plugin.CrudView.Users'];

    protected CrudViewHelper $CrudView;

    protected View $View;

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

        $this->CrudView = new CrudViewHelper($this->View);

        $this->fetchTable('Blogs')->belongsTo('Users');

        static::setAppNamespace();
    }

    public function testIntrospect()
    {
        $entity = $this->fetchTable('Blogs')->find()->first();
        $entity->created = new DateTime();

        $this->CrudView->setContext($entity);

        $value = $entity->created;
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

    public function testProcess()
    {
        $entity = $this->fetchTable('Blogs')->find()
            ->contain('Users')
            ->first();

        $this->assertSame(
            'on 1/1/00',
            $this->CrudView->process('user.birth_date', $entity)
        );
    }
}
