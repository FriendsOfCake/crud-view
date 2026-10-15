<?php
declare(strict_types=1);

namespace CrudView\Listener\Traits;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Crud\Action\BaseAction;

trait SiteTitleTrait
{
    /**
     * beforeRender event
     *
     * @return void
     */
    public function beforeRenderSiteTitle(): void
    {
        $controller = $this->controller();

        $controller->set('siteTitle', $this->getSiteTitle());
        $controller->set('siteTitleLink', $this->getSiteTitleLink());
        $controller->set('siteTitleImage', $this->getSiteTitleImage());
    }

    /**
     * Get the brand name to use in the default template.
     *
     * @return string
     */
    protected function getSiteTitle(): string
    {
        $action = $this->action();

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
    protected function getSiteTitleLink(): string
    {
        $action = $this->action();

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
    protected function getSiteTitleImage(): string
    {
        $action = $this->action();

        $image = $action->getConfig('scaffold.site_title_image');
        if (empty($image)) {
            $image = '';
        }

        return $image;
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
