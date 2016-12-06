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
        $controller = $this->_controller();
        $controller->set('indexType', $this->_getIndexType());
        $controller->set('indexBlogTitleField', $this->_getIndexBlogTitleField());
        $controller->set('indexBlogBodyField', $this->_getIndexBlogBodyField());
    }

    /**
     * Returns the index type to show on scaffolded view
     *
     * @return string
     */
    protected function _getIndexType()
    {
        $action = $this->_action();

        $indexType = $action->config('scaffold.index_type');
        if (empty($indexType)) {
            $indexType = 'table';
        }

        return $indexType;
    }

    /**
     * Returns the blog title field to show on scaffolded view
     *
     * @return string
     */
    protected function _getIndexBlogTitleField()
    {
        $action = $this->_action();

        $field = $action->config('scaffold.index_blog_title_field');
        if (empty($field)) {
            $field = 'title';
        }

        return $field;
    }

    /**
     * Returns the blog body field to show on scaffolded view
     *
     * @return string
     */
    protected function _getIndexBlogBodyField()
    {
        $action = $this->_action();

        $field = $action->config('scaffold.index_blog_body_field');
        if (empty($field)) {
            $field = 'body';
        }

        return $field;
    }
}
