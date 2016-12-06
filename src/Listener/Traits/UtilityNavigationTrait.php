<?php
namespace CrudView\Listener\Traits;

use Cake\Event\Event;

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

        $utilityNavigation = $action->config('scaffold.utility_navigation');
        if (empty($utilityNavigation)) {
            $utilityNavigation = null;
        }

        return $utilityNavigation;
    }
}
