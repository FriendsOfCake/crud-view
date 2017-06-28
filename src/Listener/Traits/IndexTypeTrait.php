<?php
namespace CrudView\Listener\Traits;

use Cake\Event\Event;

trait IndexTypeTrait
{
    /**
     * beforeRender event
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeRenderIndexType(Event $event)
    {
        $indexTitleField = $this->_getIndexTitleField();
        $indexBodyField = $this->_getIndexBodyField();

        $controller = $this->_controller();
        $controller->set('indexFinderScopes', $this->_getIndexFinderScopes());
        $controller->set('indexFormats', $this->_getIndexFormats());
        $controller->set('indexType', $this->_getIndexType());
        $controller->set('indexTitleField', $indexTitleField);
        $controller->set('indexBodyField', $indexBodyField);
        $controller->set('indexImageField', $this->_getIndexImageField());
        $controller->seT('indexGalleryCssClasses', $this->_getIndexGalleryCssClasses());

        $controller->set('indexBlogTitleField', $indexTitleField);
        $controller->set('indexBlogBodyField', $indexBodyField);
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
            $field = $action->getConfig('scaffold.index_blog_title_field');
            if ($field !== null) {
                $this->deprecatedScaffoldKeyNotice(
                    'scaffold.index_blog_title_field',
                    'scaffold.index_title_field'
                );
            }
        }

        if (empty($field)) {
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
            $field = $action->getConfig('scaffold.index_blog_body_field');
            if ($field !== null) {
                $this->deprecatedScaffoldKeyNotice(
                    'scaffold.index_blog_body_field',
                    'scaffold.index_body_field'
                );
            }
        }

        if (empty($field)) {
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

    /**
     * {@inheritDoc}
     * @param string $deprecatedKey
     * @param string $newKey
     */
    abstract protected function deprecatedScaffoldKeyNotice($deprecatedKey, $newKey);
}
