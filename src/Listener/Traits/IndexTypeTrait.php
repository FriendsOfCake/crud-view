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
}
