<?php
namespace CrudView\Listener\Traits;

use Cake\Core\Configure;

trait SiteTitleTrait
{
    /**
     * beforeRender event
     *
     * @return void
     */
    public function beforeRenderSiteTitle()
    {
        $controller = $this->_controller();

        $controller->set('siteTitle', $this->_getSiteTitle());
        $controller->set('siteTitleLink', $this->_getSiteTitleLink());
        $controller->set('siteTitleImage', $this->_getSiteTitleImage());
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

        return Configure::read('CrudView.siteTitle');
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
    abstract protected function _action(?string $name = null);
}
