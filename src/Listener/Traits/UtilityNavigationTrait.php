<?php
namespace CrudView\Listener\Traits;

use Cake\Event\Event;
use CrudView\Menu\MenuItem;

trait UtilityNavigationTrait
{
    /**
     * beforeRender event
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeRenderUtilityNavigation(Event $event)
    {
        $controller = $this->_controller();
        $controller->set('utilityNavigation', $this->_getUtilityNavigation());
    }

    /**
     * Returns the utility navigation to show on scaffolded view
     *
     * @return string
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
    abstract protected function _action($name = null);
}
