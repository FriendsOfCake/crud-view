<?php
declare(strict_types=1);

namespace CrudView\Listener\Traits;

use Cake\Controller\Controller;
use Cake\Datasource\RepositoryInterface;
use Crud\Action\BaseAction;
use Crud\Action\IndexAction;

trait IndexTypeTrait
{
    /**
     * beforeRender event
     *
     * @return void
     */
    public function beforeRenderIndexType(): void
    {
        if (!($this->action() instanceof IndexAction)) {
            return;
        }

        $controller = $this->controller();
        $controller->set('indexFinderScopes', $this->getIndexFinderScopes());
        $controller->set('indexFormats', $this->getIndexFormats());
        $controller->set('indexType', $this->getIndexType());
        $controller->set('indexTitleField', $this->getIndexTitleField());
        $controller->set('indexBodyField', $this->getIndexBodyField());
        $controller->set('indexImageField', $this->getIndexImageField());
        $controller->set('indexGalleryCssClasses', $this->getIndexGalleryCssClasses());
    }

    /**
     * Returns a list of finder scopes, where the key is the title
     * and the value is the finder to link to
     *
     * @return array
     */
    protected function getIndexFinderScopes(): array
    {
        $action = $this->action();

        return $action->getConfig('scaffold.index_finder_scopes') ?: [];
    }

    /**
     * Returns a list of index formats, where the key is the link title
     * and the value is the url to route to
     *
     * @return array
     */
    protected function getIndexFormats(): array
    {
        $action = $this->action();

        return $action->getConfig('scaffold.index_formats') ?: [];
    }

    /**
     * Returns the index type to show on scaffolded view
     *
     * @return string
     */
    protected function getIndexType(): string
    {
        $action = $this->action();

        $indexType = $action->getConfig('scaffold.index_type');
        if (empty($indexType)) {
            $indexType = 'table';
        }

        return $indexType;
    }

    /**
     * Returns the title field to show on scaffolded view
     *
     * @return string
     */
    protected function getIndexTitleField(): string
    {
        $action = $this->action();

        $field = $action->getConfig('scaffold.index_title_field');
        if ($field === null) {
            $field = $this->model()->getDisplayField();
        }

        return $field;
    }

    /**
     * Returns the body field to show on scaffolded view
     *
     * @return string
     */
    protected function getIndexBodyField(): string
    {
        $action = $this->action();

        $field = $action->getConfig('scaffold.index_body_field');
        if ($field === null) {
            $field = 'body';
        }

        return $field;
    }

    /**
     * Returns the image field to show on scaffolded view
     *
     * @return string
     */
    protected function getIndexImageField(): string
    {
        $action = $this->action();

        $field = $action->getConfig('scaffold.index_image_field');
        if (empty($field)) {
            $field = 'image';
        }

        return $field;
    }

    /**
     * Returns the css classes to use for each gallery entry
     *
     * @return string
     */
    protected function getIndexGalleryCssClasses(): string
    {
        $action = $this->action();

        $field = $action->getConfig('scaffold.index_gallery_css_classes');
        if (empty($field)) {
            $field = 'col-sm-6 col-md-3';
        }

        return $field;
    }

    /**
     * @inheritDoc
     */
    abstract protected function controller(): Controller;

    /**
     * @inheritDoc
     */
    abstract protected function action(?string $name = null): BaseAction;

    /**
     * @inheritDoc
     */
    abstract protected function model(): RepositoryInterface;
}
