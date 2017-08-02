<?php
namespace CrudView\Listener\Traits;

trait SidebarNavigationTrait
{
    /**
     * beforeRender event
     *
     * @return void
     */
    public function beforeRenderSidebarNavigation()
    {
        $controller = $this->_controller();
        $sidebarNavigation = $this->_getSidebarNavigation();
        $controller->set('disableSidebar', ($sidebarNavigation === false) ? true : false);
        $controller->set('sidebarNavigation', $sidebarNavigation);
    }

    /**
     * Returns the sidebar navigation to show on scaffolded view
     *
     * @return string|null|false
     */
    protected function _getSidebarNavigation()
    {
        $action = $this->_action();

        return $action->getConfig('scaffold.sidebar_navigation');
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
