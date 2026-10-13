<?php
declare(strict_types=1);

namespace CrudView\Listener\Traits;

use Cake\Controller\Controller;
use Crud\Action\BaseAction;

trait SidebarNavigationTrait
{
    /**
     * beforeRender event
     *
     * @return void
     */
    public function beforeRenderSidebarNavigation(): void
    {
        $controller = $this->controller();
        $sidebarNavigation = $this->getSidebarNavigation();
        $controller->set('disableSidebar', $sidebarNavigation === false ? true : false);
        $controller->set('sidebarNavigation', $sidebarNavigation);
    }

    /**
     * Returns the sidebar navigation to show on scaffolded view
     *
     * @return string|false|null
     */
    protected function getSidebarNavigation(): string|false|null
    {
        $action = $this->action();

        return $action->getConfig('scaffold.sidebar_navigation');
    }

    /**
     * @inheritDoc
     */
    abstract protected function controller(): Controller;

    /**
     * @inheritDoc
     */
    abstract protected function action(?string $name = null): BaseAction;
}
