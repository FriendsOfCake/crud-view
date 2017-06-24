<?php
namespace CrudView\Listener\Traits;

use Cake\Event\Event;

trait FormTypeTrait
{
    /**
     * beforeRender event
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeRenderFormType(Event $event)
    {
        $controller = $this->_controller();
        $controller->set('formEnableDirtyCheck', $this->_getFormEnableDirtyCheck());
        $controller->set('formSubmitButtonText', $this->_getFormSubmitButtonText());
        $controller->set('formSubmitExtraButtons', $this->_getFormSubmitExtraButtons());
        $controller->set('formUrl', $this->_getFormUrl());
    }

    /**
     * Get form enable dirty check setting
     *
     * @return bool
     */
    protected function _getFormEnableDirtyCheck()
    {
        $action = $this->_action();

        return $action->config('scaffold.form_enable_dirty_check') ?: false;
    }

    /**
     * Get form submit button text.
     *
     * @return bool
     */
    protected function _getFormSubmitButtonText()
    {
        $action = $this->_action();

        return $action->config('scaffold.form_submit_button_text') ?: __d('crud', 'Save');
    }


    /**
     * Get extra form submit buttons.
     *
     * @return bool
     */
    protected function _getFormSubmitExtraButtons()
    {
        $action = $this->_action();

        $defaults = [
            [
                'title' => __d('crud', 'Save & continue editing'),
                'options' => ['class' => 'btn btn-success btn-save-continue', 'name' => '_edit', 'value' => true],
                'type' => 'button',
            ],
            [
                'title' => __d('crud', 'Save & create new'),
                'options' => ['class' => 'btn btn-success', 'name' => '_add', 'value' => true],
                'type' => 'button',
            ],
            [
                'title' => __d('crud', 'Back'),
                'url' => ['action' => 'index'],
                'options' => ['class' => 'btn btn-default', 'role' => 'button', 'value' => true],
                'type' => 'link',
            ],
        ];

        $buttons = $action->config('scaffold.form_submit_extra_buttons');
        if ($buttons === null || $buttons === true) {
            $buttons = $defaults;
        }

        return $buttons;
    }

    /**
     * Get form url.
     *
     * @return mixed
     */
    protected function _getFormUrl()
    {
        $action = $this->_action();

        return $action->config('scaffold.form_action') ?: null;
    }
}