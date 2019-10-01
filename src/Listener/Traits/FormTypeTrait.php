<?php
declare(strict_types=1);

namespace CrudView\Listener\Traits;

use Crud\Action\EditAction;

trait FormTypeTrait
{
    /**
     * beforeRender event
     *
     * @return void
     */
    protected function beforeRenderFormType()
    {
        $controller = $this->_controller();

        $controller->set('formEnableDirtyCheck', $this->_getFormEnableDirtyCheck());
        $controller->set('formSubmitButtonText', $this->_getFormSubmitButtonText());
        $controller->set('formSubmitExtraButtons', $this->_getFormSubmitExtraButtons());
        $controller->set('formSubmitExtraLeftButtons', $this->_getFormSubmitExtraLeftButtons());
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

        return $action->getConfig('scaffold.form_enable_dirty_check') ?: false;
    }

    /**
     * Get form submit button text.
     *
     * @return bool
     */
    protected function _getFormSubmitButtonText()
    {
        $action = $this->_action();

        return $action->getConfig('scaffold.form_submit_button_text') ?: __d('crud', 'Save');
    }

    /**
     * Get extra form submit buttons.
     *
     * @return array
     */
    protected function _getFormSubmitExtraButtons()
    {
        $action = $this->_action();
        $buttons = $action->getConfig('scaffold.form_submit_extra_buttons');

        if ($buttons === false) {
            return [];
        }

        if ($buttons === null || $buttons === true) {
            $buttons = $this->_getDefaultExtraButtons();
        }

        return $buttons;
    }

    /**
     * Get extra form submit left buttons.
     *
     * @return array
     */
    protected function _getFormSubmitExtraLeftButtons()
    {
        $action = $this->_action();
        $buttons = $action->getConfig('scaffold.form_submit_extra_left_buttons');

        if ($buttons === false) {
            return [];
        }

        if ($buttons === null || $buttons === true) {
            $buttons = $this->_getDefaultExtraLeftButtons();
        }

        return $buttons;
    }

    /**
     * Get default extra buttons
     *
     * @return array
     */
    protected function _getDefaultExtraButtons()
    {
        return [
            'save_and_continue' => [
                'title' => __d('crud', 'Save & continue editing'),
                'options' => ['class' => 'btn btn-success btn-save-continue', 'name' => '_edit', 'value' => '1'],
                'type' => 'button',
                '_label' => 'save_and_continue',
            ],
            'save_and_create' => [
                'title' => __d('crud', 'Save & create new'),
                'options' => ['class' => 'btn btn-success', 'name' => '_add', 'value' => '1'],
                'type' => 'button',
                '_label' => 'save_and_create',
            ],
            'back' => [
                'title' => __d('crud', 'Back'),
                'url' => ['action' => 'index'],
                'options' => ['class' => 'btn btn-secondary', 'role' => 'button'],
                'type' => 'link',
                '_label' => 'back',
            ],
        ];
    }

    /**
     * Get default extra left buttons
     *
     * @return array
     */
    protected function _getDefaultExtraLeftButtons()
    {
        $buttons = [];

        $action = $this->_action();
        if ($action instanceof EditAction) {
            $blacklist = $action->getConfig('scaffold.actions_blacklist', []);
            if (!in_array('delete', $blacklist, true)) {
                $buttons[] = [
                    'title' => __d('crud', 'Delete'),
                    'url' => ['action' => 'delete'],
                    'options' => [
                        'block' => 'form.after_end',
                        'class' => 'btn btn-danger btn-delete',
                        'confirm' => __d('crud', 'Are you sure you want to delete this record?'),
                        'name' => '_delete',
                        'style' => 'margin-left: 0',
                    ],
                    'type' => 'postLink',
                    '_label' => 'delete',
                ];
            }
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

        return $action->getConfig('scaffold.form_action') ?: null;
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
