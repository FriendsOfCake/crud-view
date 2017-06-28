<?php
namespace CrudView\Listener\Traits;

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

        $formSubmitButtonText = $this->_getFormSubmitButtonText();

        $controller->set('formEnableDirtyCheck', $this->_getFormEnableDirtyCheck());
        $controller->set('formSubmitButtonText', $formSubmitButtonText);
        $controller->set('formSubmitExtraButtons', $this->_getFormSubmitExtraButtons());
        $controller->set('formUrl', $this->_getFormUrl());

        $controller->set('submitButtonText', $formSubmitButtonText);
        $controller->set('disableExtraButtons', $this->_getFormDisableExtraButtons());
        $controller->set('extraButtonsBlacklist', $this->_getFormExtraButtonsBlacklist());
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
     * @return bool
     */
    protected function _getFormSubmitExtraButtons()
    {
        $action = $this->_action();

        $disableExtraButtons = $this->_getFormDisableExtraButtons();
        if ($disableExtraButtons === true) {
            $this->deprecatedScaffoldKeyNotice(
                'scaffold.disable_extra_buttons',
                'scaffold.form_submit_extra_buttons'
            );

            return [];
        }

        $defaults = [
            [
                'title' => __d('crud', 'Save & continue editing'),
                'options' => ['class' => 'btn btn-success btn-save-continue', 'name' => '_edit', 'value' => true],
                'type' => 'button',
                '_label' => 'save_and_continue',
            ],
            [
                'title' => __d('crud', 'Save & create new'),
                'options' => ['class' => 'btn btn-success', 'name' => '_add', 'value' => true],
                'type' => 'button',
                '_label' => 'save_and_create',
            ],
            [
                'title' => __d('crud', 'Back'),
                'url' => ['action' => 'index'],
                'options' => ['class' => 'btn btn-default', 'role' => 'button', 'value' => true],
                'type' => 'link',
                '_label' => 'back',
            ],
        ];

        $extraButtonsBlacklist = $this->_getFormExtraButtonsBlacklist();
        if (!empty($extraButtonsBlacklist)) {
            $this->deprecatedScaffoldKeyNotice(
                'scaffold.extra_buttons_blacklist',
                'scaffold.form_submit_extra_buttons'
            );
            $newDefaults = [];
            foreach ($defaults as $default) {
                if (in_array($default['_label'], $extraButtonsBlacklist)) {
                    continue;
                }
                $newDefaults[] = $default;
            }
            $defaults = $newDefaults;
        }

        $buttons = $action->getConfig('scaffold.form_submit_extra_buttons');
        if ($buttons === null || $buttons === true) {
            $buttons = $defaults;
        }

        if ($buttons === false) {
            $buttons = [];
        }

        return $buttons;
    }

    /**
     * Disable extra buttons.
     *
     * @return bool
     * @deprecated 0.7.0 Deprecated in favor of form_submit_extra_buttons
     */
    protected function _getFormDisableExtraButtons()
    {
        $action = $this->_action();

        return $action->getConfig('scaffold.disable_extra_buttons') ?: false;
    }

    /**
     * Get extra buttons blacklist
     *
     * @return array
     * @deprecated 0.7.0 Deprecated in favor of form_submit_extra_buttons
     */
    protected function _getFormExtraButtonsBlacklist()
    {
        $action = $this->_action();

        return $action->getConfig('scaffold.extra_buttons_blacklist') ?: [];
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
    abstract protected function _action($name = null);

    /**
     * {@inheritDoc}
     * @param string $deprecatedKey
     * @param string $newKey
     */
    abstract protected function deprecatedScaffoldKeyNotice($deprecatedKey, $newKey);
}
