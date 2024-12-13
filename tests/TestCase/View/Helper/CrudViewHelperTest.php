<?php
declare(strict_types=1);

namespace CrudView\Test\TestCase\View\Helper;

use Cake\I18n\DateTime;
use Cake\I18n\Time;
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

    public function testIntrospect(): void
    {
        $entity = $this->fetchTable('Blogs')->find()->first();
        $entity->created = new DateTime();

        $this->CrudView->setContext($entity);

        $value = $entity->created;
        $result = $this->CrudView->introspect('created', $value);
        $this->assertEquals($entity->created->i18nFormat(), $result);

        $result = $this->CrudView->introspect('created', 'invalid');
        $this->assertEquals('<span class="text-bg-info badge">N/A</span>', $result);

        $result = $this->CrudView->introspect('created', null);
        $this->assertEquals('<span class="text-bg-info badge">N/A</span>', $result);

        $this->CrudView->setConfig('fieldFormatters', [
            'datetime' => function () {
                return 'formatted time';
            },
        ]);
        $result = $this->CrudView->introspect('created', $value);
        $this->assertEquals('formatted time', $result);
    }

    public function testProcess(): void
    {
        $entity = $this->fetchTable('Blogs')->find()
            ->contain('Users')
            ->first();

        $this->assertSame(
            '1/15/00',
            $this->CrudView->process('user.birth_date', $entity)
        );
    }

    public function testFormatDateTime(): void
    {
        $dateTime = new Time('14:00:00');

        $result = $this->CrudView->formatDateTime('field', $dateTime, []);
        $this->assertEquals('2:00 PM', str_replace(' ', ' ', $result));

        Time::setToStringFormat('KK:mm:ss a');
        $result = $this->CrudView->formatDateTime('field', $dateTime, []);
        $this->assertEquals('02:00:00 PM', $result);

        $dateTime = new DateTime('2021-01-20 14:00:00');
        $result = $this->CrudView->formatDateTime('field', $dateTime, []);
        $this->assertEquals('1/20/21, 2:00 PM', str_replace(' ', ' ', $result));
    }
}
