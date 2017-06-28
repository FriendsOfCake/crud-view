<?php
namespace CrudView\Listener\Traits;

use Cake\Core\Configure;
use Cake\Event\Event;

trait SiteTitleTrait
{
    /**
     * beforeRender event
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeRenderSiteTitle(Event $event)
    {
        $controller = $this->_controller();

        $siteTitle = $this->_getSiteTitle();
        $controller->set('siteTitle', $siteTitle);
        $controller->set('siteTitleLink', $this->_getSiteTitleLink());
        $controller->set('siteTitleImage', $this->_getSiteTitleImage());

        // deprecated
        $controller->set('brand', $siteTitle);
    }

    /**
     * Get the brand name to use in the default template.
     *
     * @return string
     */
    protected function _getSiteTitle()
    {
        $action = $this->_action();

        $title = $action->getConfig('scaffold.site_title');
        if (!empty($title)) {
            return $title;
        }

        $title = Configure::read('CrudView.siteTitle');
        if (!empty($title)) {
            return $title;
        }

        // deprecated
        $title = $action->getConfig('scaffold.brand');
        if (!empty($title)) {
            return $title;
        }

        return Configure::read('CrudView.brand');
    }

    /**
     * Returns the sites title link to show on scaffolded view
     *
     * @return string
     */
    protected function _getSiteTitleLink()
    {
        $action = $this->_action();

        $link = $action->getConfig('scaffold.site_title_link');
        if (empty($link)) {
            $link = '';
        }

        return $link;
    }

    /**
     * Returns the sites title image to show on scaffolded view
     *
     * @return string
     */
    protected function _getSiteTitleImage()
    {
        $action = $this->_action();

        $image = $action->getConfig('scaffold.site_title_image');
        if (empty($image)) {
            $image = '';
        }

        return $image;
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
