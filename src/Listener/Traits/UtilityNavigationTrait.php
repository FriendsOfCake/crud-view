<?php
declare(strict_types=1);

namespace CrudView\Listener\Traits;

use Cake\Controller\Controller;
use Crud\Action\BaseAction;
use CrudView\Menu\MenuItem;

trait UtilityNavigationTrait
{
    /**
     * beforeRender event
     *
     * @return void
     */
    public function beforeRenderUtilityNavigation(): void
    {
        $controller = $this->controller();
        $controller->set('utilityNavigation', $this->getUtilityNavigation());
    }

    /**
     * Returns the utility navigation to show on scaffolded view
     *
     * @return array
     */
    protected function getUtilityNavigation(): array
    {
        $action = $this->action();

        $utilityNavigation = $action->getConfig('scaffold.utility_navigation');
        if ($utilityNavigation === null) {
            $utilityNavigation = [
                new MenuItem('Account', ['controller' => 'Users', 'action' => 'account']),
                new MenuItem('Log Out', ['controller' => 'Users', 'action' => 'logout']),
            ];
        }

        return $utilityNavigation;
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
