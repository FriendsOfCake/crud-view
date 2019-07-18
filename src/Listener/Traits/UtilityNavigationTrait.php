<?php
namespace CrudView\Listener\Traits;

use CrudView\Menu\MenuItem;

trait UtilityNavigationTrait
{
    /**
     * beforeRender event
     *
     * @return void
     */
    public function beforeRenderUtilityNavigation()
    {
        $controller = $this->_controller();
        $controller->set('utilityNavigation', $this->_getUtilityNavigation());
    }

    /**
     * Returns the utility navigation to show on scaffolded view
     *
     * @return array
     */
    protected function _getUtilityNavigation()
    {
        $action = $this->_action();

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
     * {@inheritDoc}
     */
    abstract protected function _controller();

    /**
     * {@inheritDoc}
     */
    abstract protected function _action(?string $name = null);
}
