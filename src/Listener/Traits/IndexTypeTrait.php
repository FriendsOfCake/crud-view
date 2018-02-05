<?php
namespace CrudView\Listener\Traits;

use Crud\Action\IndexAction;

trait IndexTypeTrait
{
    /**
     * beforeRender event
     *
     * @return void
     */
    public function beforeRenderIndexType()
    {
        if (!($this->_action() instanceof IndexAction)) {
            return;
        }

        $controller = $this->_controller();
        $controller->set('indexFinderScopes', $this->_getIndexFinderScopes());
        $controller->set('indexFormats', $this->_getIndexFormats());
        $controller->set('indexType', $this->_getIndexType());
        $controller->set('indexTitleField', $this->_getIndexTitleField());
        $controller->set('indexBodyField', $this->_getIndexBodyField());
        $controller->set('indexImageField', $this->_getIndexImageField());
        $controller->set('indexGalleryCssClasses', $this->_getIndexGalleryCssClasses());
    }

    /**
     * Returns a list of finder scopes, where the key is the title
     * and the value is the finder to link to
     *
     * @return string
     */
    protected function _getIndexFinderScopes()
    {
        $action = $this->_action();

        return $action->getConfig('scaffold.index_finder_scopes') ?: [];
    }

    /**
     * Returns a list of index formats, where the key is the link title
     * and the value is the url to route to
     *
     * @return string
     */
    protected function _getIndexFormats()
    {
        $action = $this->_action();

        return $action->getConfig('scaffold.index_formats') ?: [];
    }

    /**
     * Returns the index type to show on scaffolded view
     *
     * @return string
     */
    protected function _getIndexType()
    {
        $action = $this->_action();

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
    protected function _getIndexTitleField()
    {
        $action = $this->_action();

        $field = $action->getConfig('scaffold.index_title_field');
        if ($field === null) {
            $field = $this->_table()->getDisplayField();
        }

        return $field;
    }

    /**
     * Returns the body field to show on scaffolded view
     *
     * @return string
     */
    protected function _getIndexBodyField()
    {
        $action = $this->_action();

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
    protected function _getIndexImageField()
    {
        $action = $this->_action();

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
    protected function _getIndexGalleryCssClasses()
    {
        $action = $this->_action();

        $field = $action->getConfig('scaffold.index_gallery_css_classes');
        if (empty($field)) {
            $field = 'col-sm-6 col-md-3';
        }

        return $field;
    }

    /**
     * {@inheritDoc}
     */
    abstract protected function _controller();

    /**
     * {@inheritDoc}
     */
    abstract protected function _action($name = null);

    /**
     * {@inheritDoc}
     */
    abstract protected function _table();
}
