<?php
declare(strict_types=1);

namespace CrudView\Listener\Traits;

use Cake\Controller\Controller;
use Crud\Action\BaseAction;
use Crud\Action\EditAction;
use function Cake\I18n\__d;

trait FormTypeTrait
{
    /**
     * beforeRender event
     *
     * @return void
     */
    protected function beforeRenderFormType(): void
    {
        $controller = $this->controller();

        $controller->set('formEnableDirtyCheck', $this->getFormEnableDirtyCheck());
        $controller->set('formSubmitButtonText', $this->getFormSubmitButtonText());
        $controller->set('formSubmitExtraButtons', $this->getFormSubmitExtraButtons());
        $controller->set('formSubmitExtraLeftButtons', $this->getFormSubmitExtraLeftButtons());
        $controller->set('formUrl', $this->getFormUrl());
    }

    /**
     * Get form enable dirty check setting
     *
     * @return bool
     */
    protected function getFormEnableDirtyCheck(): bool
    {
        $action = $this->action();

        return $action->getConfig('scaffold.form_enable_dirty_check') ?: false;
    }

    /**
     * Get form submit button text.
     *
     * @return string
     */
    protected function getFormSubmitButtonText(): string
    {
        $action = $this->action();

        return $action->getConfig('scaffold.form_submit_button_text') ?: __d('crud', 'Save');
    }

    /**
     * Get extra form submit buttons.
     *
     * @return array
     */
    protected function getFormSubmitExtraButtons(): array
    {
        $action = $this->action();
        $buttons = $action->getConfig('scaffold.form_submit_extra_buttons');

        if ($buttons === false) {
            return [];
        }

        if ($buttons === null || $buttons === true) {
            $buttons = $this->getDefaultExtraButtons();
        }

        return $buttons;
    }

    /**
     * Get extra form submit left buttons.
     *
     * @return array
     */
    protected function getFormSubmitExtraLeftButtons(): array
    {
        $action = $this->action();
        $buttons = $action->getConfig('scaffold.form_submit_extra_left_buttons');

        if ($buttons === false) {
            return [];
        }

        if ($buttons === null || $buttons === true) {
            $buttons = $this->getDefaultExtraLeftButtons();
        }

        return $buttons;
    }

    /**
     * Get default extra buttons
     *
     * @return array
     */
    protected function getDefaultExtraButtons(): array
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
    protected function getDefaultExtraLeftButtons(): array
    {
        $buttons = [];

        $action = $this->action();
        if ($action instanceof EditAction) {
            $blacklist = $action->getConfig('scaffold.actions_blacklist', []);
            if (
                !in_array('delete', $blacklist, true) &&
                $this->crud()->isActionMapped('delete') &&
                $this->crud()->action('delete')->enabled()
            ) {
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
     * @return array|string|null
     */
    protected function getFormUrl(): array|string|null
    {
        $action = $this->action();

        return $action->getConfig('scaffold.form_action') ?: null;
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
