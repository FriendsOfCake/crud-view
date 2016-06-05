<?php
namespace CrudView\Listener;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use CrudView\Traits\CrudViewConfigTrait;
use Crud\Listener\BaseListener;

class ViewListener extends BaseListener
{
    use CrudViewConfigTrait;

    /**
     * Default associations config
     *
     * @var array
     */
    protected $associations = [];

    /**
     * [beforeFind description]
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeFind(Event $event)
    {
        $this->associations = $this->_associations(array_keys($this->_getRelatedModels()));
        if (!$event->subject()->query->contain()) {
            $event->subject()->query->contain($this->_getRelatedModels());
        }
    }

    /**
     * [beforePaginate description]
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforePaginate(Event $event)
    {
        $this->associations = $this->_associations(array_keys($this->_getRelatedModels()));
        if (!$event->subject()->query->contain()) {
            $event->subject()->query->contain($this->_getRelatedModels(['manyToOne', 'oneToOne']));
        }
    }

    /**
     * beforeRender event
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if ($this->_controller()->name === 'CakeError') {
            return;
        }

        if (!empty($event->subject()->entity)) {
            $this->_entity = $event->subject()->entity;
        }

        if (empty($this->associations)) {
            $this->associations = $this->_associations(array_keys($this->_getRelatedModels()));
        }

        $this->ensureConfig();

        $controller = $this->_controller();
        $controller->set('actionConfig', $this->_action()->config());
        $controller->set('brand', $this->_getBrand());
        $controller->set('title', $this->_getPageTitle());
        $associations = $this->associations;
        $controller->set(compact('associations'));
        $controller->set('fields', $this->_scaffoldFields($associations));
        $controller->set('blacklist', $this->_blacklist());
        $controller->set('actions', $this->_getControllerActions());
        $controller->set('bulkActions', $this->_getBulkActions());
        $controller->set('viewblocks', $this->_getViewBlocks());
        $controller->set('formUrl', $this->_getFormUrl());
        $controller->set('disableExtraButtons', $this->_getDisableExtraButtons());
        $controller->set('disableSidebar', $this->_getDisableSidebar());
        $controller->set('extraButtonsBlacklist', $this->_getExtraButtonsBlacklist());
        $controller->set('enableDirtyCheck', $this->_getEnableDirtyCheck());
        $controller->set('actionGroups', $this->_getActionGroups());
        $controller->set($this->_getPageVariables());
    }

    /**
     * Make sure the CrudView config exists
     *
     * If it doesn't, load the defaults file
     *
     * @return bool
     */
    public function ensureConfig()
    {
        $config = Configure::read('CrudView');
        if ($config !== null) {
            return true;
        }

        return Configure::load('CrudView.defaults');
    }

    /**
     * Make sure flash messages are properly handled by BootstrapUI.FlashHelper
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function setFlash(Event $event)
    {
        unset($event->subject()->params['class']);
        $event->subject()->element = ltrim($event->subject()->type);
    }

    /**
     * Get the brand name to use in the default template.
     *
     * @return string
     */
    protected function _getBrand()
    {
        $brand = $this->_action()->config('scaffold.brand');
        if (!empty($brand)) {
            return $brand;
        }

        return Configure::read('CrudView.brand');
    }

    /**
     * Get a list of relevant models to contain using Containable
     *
     * If the user haven't configured any relations for an action
     * we assume all relations will be used.
     *
     * The user can chose to suppress specific relations using the blacklist
     * functionality.
     *
     * @param array $relations List of relations.
     * @return array
     */
    protected function _getRelatedModels($relations = [])
    {
        $models = $this->_action()->config('scaffold.relations');

        if ($models === false) {
            return [];
        }

        if (empty($models)) {
            $associations = $this->_associations();

            if (empty($relations)) {
                $relations = array_keys($associations);
            }

            $models = [];
            foreach ($relations as $relation) {
                $models = Hash::merge($models, (array)Hash::extract($associations, "$relation.{s}.model"));
            }
        }

        $models = Hash::normalize($models);

        $blacklist = $this->_action()->config('scaffold.relations_blacklist');
        if (!empty($blacklist)) {
            $blacklist = Hash::normalize($blacklist);
            $models = array_diff_key($models, $blacklist);
        }

        foreach ($models as $key => $value) {
            if (!is_array($value)) {
                $models[$key] = [];
            }
        }

        return $models;
    }

    /**
     * Get list of blacklisted fields from config.
     *
     * @return array
     */
    protected function _blacklist()
    {
        return (array)$this->_action()->config('scaffold.fields_blacklist');
    }

    /**
     * Publish fairly static variables needed in the view
     *
     * @return array
     */
    protected function _getPageVariables()
    {
        $table = $this->_table();
        $controller = $this->_controller();
        $scope = $this->_action()->config('scope');

        $data = [
            'modelClass' => $controller->modelClass,
            'modelSchema' => $table->schema(),
            'displayField' => $table->displayField(),
            'singularHumanName' => Inflector::humanize(Inflector::underscore(Inflector::singularize($controller->modelClass))),
            'pluralHumanName' => Inflector::humanize(Inflector::underscore($controller->name)),
            'singularVar' => Inflector::singularize($controller->name),
            'pluralVar' => Inflector::variable($controller->name),
            'primaryKey' => $table->primaryKey(),
        ];

        if ($scope === 'entity') {
            $data += [
                'primaryKeyValue' => $this->_primaryKeyValue()
            ];
        }

        return $data;
    }

    /**
     * Returns the page title to show on scaffolded view
     *
     * @return string
     */
    protected function _getPageTitle()
    {
        $action = $this->_action();

        $title = $action->config('scaffold.page_title');
        if (!empty($title)) {
            return $title;
        }

        $scope = $action->config('scope');

        $request = $this->_request();
        $actionName = Inflector::humanize(Inflector::underscore($request->action));
        $controllerName = $this->_controllerName();

        if ($scope === 'table') {
            if ($actionName === 'Index') {
                return $controllerName;
            }

            return sprintf('%s %s', $controllerName, $actionName);
        }

        $primaryKeyValue = $this->_primaryKeyValue();
        if ($primaryKeyValue === null) {
            return sprintf('%s %s', $actionName, $controllerName);
        }

        $displayFieldValue = $this->_displayFieldValue();
        if ($displayFieldValue === null || $this->_table()->displayField() == $this->_table()->primaryKey()) {
            return sprintf('%s %s #%s', $actionName, $controllerName, $primaryKeyValue);
        }

        return sprintf('%s %s #%s: %s', $actionName, $controllerName, $primaryKeyValue, $displayFieldValue);
    }

    /**
     * Returns fields to be displayed on scaffolded template
     *
     * @param array $associations Associations list.
     * @return array
     */
    protected function _scaffoldFields(array $associations = [])
    {
        $action = $this->_action();
        $scaffoldFields = (array)$action->config('scaffold.fields');
        if (!empty($scaffoldFields)) {
            $scaffoldFields = Hash::normalize($scaffoldFields);
        }

        if (empty($scaffoldFields) || $action->config('scaffold.autoFields')) {
            $cols = $this->_table()->schema()->columns();
            $cols = Hash::normalize($cols);

            $scope = $action->config('scope');
            if ($scope === 'entity' && !empty($associations['manyToMany'])) {
                foreach ($associations['manyToMany'] as $alias => $options) {
                    $cols[sprintf('%s._ids', $options['entities'])] = [
                        'multiple' => true
                    ];
                }
            }

            $scaffoldFields = array_merge($cols, $scaffoldFields);
        }

        // Check for blacklisted fields
        $blacklist = $action->config('scaffold.fields_blacklist');
        if (!empty($blacklist)) {
            $scaffoldFields = array_diff_key($scaffoldFields, array_combine($blacklist, $blacklist));
        }

        // Make sure all array values are an array
        foreach ($scaffoldFields as $field => $options) {
            if (!is_array($options)) {
                $scaffoldFields[$field] = (array)$options;
            }

            $scaffoldFields[$field] += ['formatter' => null];
        }

        $fieldSettings = $action->config('scaffold.field_settings');
        if (empty($fieldSettings)) {
            $fieldSettings = [];
        }
        $fieldSettings = array_intersect_key($fieldSettings, $scaffoldFields);
        $scaffoldFields = Hash::merge($scaffoldFields, $fieldSettings);

        return $scaffoldFields;
    }

    /**
     * Get the controller name based on the Crud Action scope
     *
     * @return string
     */
    protected function _controllerName()
    {
        $inflections = [
            'underscore',
            'humanize',
        ];

        if ($this->_action()->scope() === 'entity') {
            $inflections[] = 'singularize';
        }

        $baseName = (string)$this->_controller()->name;
        foreach ($inflections as $inflection) {
            $baseName = Inflector::$inflection($baseName);
        }

        return $baseName;
    }

    /**
     * Returns groupings of action types on the scaffolded view
     *
     * @return array
     */
    protected function _getControllerActions()
    {
        $table = $entity = [];

        $actions = $this->_getAllowedActions();
        foreach ($actions as $actionName => $config) {
            if ($this->_crud()->isActionMapped($actionName)) {
                $action = $this->_action($actionName);
                $class = get_class($action);
                $class = substr($class, strrpos($class, '\\') + 1);
                $config['scope'] = $action->scope();

                if ($class === 'DeleteAction') {
                    $config['method'] = 'DELETE';
                }

                if ($class === 'AddAction') {
                    $config['scope'] = 'table';
                }
            }

            // apply defaults if necessary
            $scope = isset($config['scope']) ? $config['scope'] : 'entity';
            $method = isset($config['method']) ? $config['method'] : 'GET';

            $title = !empty($config['link_title']) ? $config['link_title'] : Inflector::humanize(Inflector::underscore($actionName));

            ${$scope}[$actionName] = [
                'title' => $title,
                'url' => [
                    'action' => $actionName
                ],
                'method' => $method,
                'options' => array_diff_key(
                    $config,
                    array_flip(['method', 'scope', 'className', 'link_title'])
                )
            ];
            if (!empty($config['callback'])) {
                ${$scope}[$actionName]['callback'] = $config['callback'];
            }
        }

        return compact('table', 'entity');
    }

    /**
     * Returns a list of action configs that are allowed to be shown
     *
     * @return array
     */
    protected function _getAllowedActions()
    {
        $actions = $this->_action()->config('scaffold.actions');
        if ($actions === null) {
            $actions = $this->_crud()->config('actions');
        }

        $extraActions = $this->_action()->config('scaffold.extra_actions') ?: [];

        $allActions = array_merge(
            $this->_normalizeActions($actions),
            $this->_normalizeActions($extraActions)
        );

        $blacklist = (array)$this->_action()->config('scaffold.actions_blacklist');
        $blacklist = array_combine($blacklist, $blacklist);

        return array_diff_key($allActions, $blacklist);
    }

    /**
     * Convert mixed action configs to unified structure
     *
     * [
     *   'ACTION_1' => [..config...],
     *   'ACTION_2' => [..config...],
     *   'ACTION_N' => [..config...]
     * ]
     *
     * @param array $actions Actions
     * @return array
     */
    protected function _normalizeActions($actions)
    {
        $normalized = [];
        foreach ($actions as $key => $config) {
            if (is_array($config)) {
                $normalized[$key] = $config;
            } else {
                $normalized[$config] = [];
            }
        }
        return $normalized;
    }

    /**
     * Returns associations for controllers models.
     *
     * @param array $whitelist Whitelist of associations to return.
     * @return array Associations for model
     */
    protected function _associations(array $whitelist = [])
    {
        $table = $this->_table();

        $associationConfiguration = [];

        $associations = $table->associations();

        $keys = $associations->keys();
        if ($whitelist) {
            $keys = array_intersect($keys, array_map('strtolower', $whitelist));
        }
        foreach ($keys as $associationName) {
            $association = $associations->get($associationName);
            $type = $association->type();

            if (!isset($associationConfiguration[$type])) {
                $associationConfiguration[$type] = [];
            }

            $assocKey = $association->name();
            $associationConfiguration[$type][$assocKey]['model'] = $assocKey;
            $associationConfiguration[$type][$assocKey]['type'] = $type;
            $associationConfiguration[$type][$assocKey]['primaryKey'] = $association->target()->primaryKey();
            $associationConfiguration[$type][$assocKey]['displayField'] = $association->target()->displayField();
            $associationConfiguration[$type][$assocKey]['foreignKey'] = $association->foreignKey();
            $associationConfiguration[$type][$assocKey]['propertyName'] = $association->property();
            $associationConfiguration[$type][$assocKey]['plugin'] = null;
            $associationConfiguration[$type][$assocKey]['controller'] = $assocKey;
            $associationConfiguration[$type][$assocKey]['entity'] = Inflector::singularize(Inflector::underscore($assocKey));
            $associationConfiguration[$type][$assocKey]['entities'] = Inflector::underscore($assocKey);

            $associationConfiguration[$type][$assocKey] = array_merge($associationConfiguration[$type][$assocKey], $this->_action()->config('association.' . $assocKey) ?: []);
        }

        return $associationConfiguration;
    }

    /**
     * Derive the Model::primaryKey value from the current context
     *
     * If no value can be found, NULL is returned
     *
     * @return mixed
     */
    protected function _primaryKeyValue()
    {
        return $this->_deriveFieldFromContext($this->_table()->primaryKey());
    }

    /**
     * Derive the Model::displayField value from the current context
     *
     * If no value can be found, NULL is returned
     *
     * @return string
     */
    protected function _displayFieldValue()
    {
        return $this->_deriveFieldFromContext($this->_table()->displayField());
    }

    /**
     * Extract a field value from a either the CakeRequest::$data
     * or Controller::$viewVars for the current model + the supplied field
     *
     * @param string $field Name of field.
     * @return mixed
     */
    protected function _deriveFieldFromContext($field)
    {
        $controller = $this->_controller();
        $entity = $this->_entity();
        $request = $this->_request();
        $value = $entity->get($field);

        if ($value) {
            return $value;
        }

        $path = "{$controller->modelClass}.{$field}";
        if (!empty($request->data)) {
            $value = Hash::get((array)$request->data, $path);
        }

        $singularVar = Inflector::variable($controller->modelClass);
        if (!empty($controller->viewVars[$singularVar])) {
            $value = $entity->get($field);
        }

        return $value;
    }

    /**
     * Get view blocks.
     *
     * @return array
     */
    protected function _getViewBlocks()
    {
        $action = $this->_action();
        return $action->config('scaffold.viewblocks') ?: [];
    }

    /**
     * Get bulk actions blocks.
     *
     * @return array
     */
    protected function _getBulkActions()
    {
        $action = $this->_action();
        return $action->config('scaffold.bulk_actions') ?: [];
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

    /**
     * Disable extra buttons.
     *
     * @return bool
     */
    protected function _getDisableExtraButtons()
    {
        $action = $this->_action();
        return $action->config('scaffold.disable_extra_buttons') ?: false;
    }

    /**
     * Disable sidebar.
     *
     * @return bool
     */
    protected function _getDisableSidebar()
    {
        $action = $this->_action();
        return $action->config('scaffold.disable_sidebar') ?: false;
    }

    /**
     * Get extra buttons blacklist
     *
     * @return array
     */
    protected function _getExtraButtonsBlacklist()
    {
        $action = $this->_action();
        return $action->config('scaffold.extra_buttons_blacklist') ?: [];
    }

    /**
     * Get enable dirty check setting
     *
     * @return bool
     */
    protected function _getEnableDirtyCheck()
    {
        $action = $this->_action();
        return $action->config('scaffold.enable_dirty_check') ?: false;
    }

    /**
     * Get action groups
     *
     * @return array
     */
    protected function _getActionGroups()
    {
        $action = $this->_action();
        $groups = $action->config('scaffold.action_groups') ?: [];

        $groupedActions = (new Collection($groups))->unfold()->toList();

        // add "primary" actions (primary should rendered as separate buttons)
        $groups['primary'] = array_diff(array_keys($this->_getAllowedActions()), $groupedActions);
        return $groups;
    }
}
