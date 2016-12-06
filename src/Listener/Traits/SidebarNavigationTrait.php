<?php
namespace CrudView\Listener\Traits;

use Cake\Event\Event;

trait SidebarNavigationTrait
{
    /**
     * beforeRender event
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeRenderSidebarNavigation(Event $event)
    {
        $controller = $this->_controller();
        $controller->set('sidebarNavigation', $this->_getSidebarNavigation());
    }

    /**
     * Returns the sidebar navigation to show on scaffolded view
     *
     * @return string
     */
    protected function _getSidebarNavigation()
    {
        $action = $this->_action();

        return $action->config('scaffold.sidebar_navigation');
    }
}
