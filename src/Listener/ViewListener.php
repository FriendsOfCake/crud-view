<?php
declare(strict_types=1);

namespace CrudView\Listener;

use Cake\Collection\Collection;
use Cake\Database\Exception\DatabaseException;
use Cake\Event\EventInterface;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Crud\Listener\BaseListener;
use CrudView\Listener\Traits\FormTypeTrait;
use CrudView\Listener\Traits\IndexTypeTrait;
use CrudView\Listener\Traits\SidebarNavigationTrait;
use CrudView\Listener\Traits\SiteTitleTrait;
use CrudView\Listener\Traits\UtilityNavigationTrait;
use CrudView\Traits\CrudViewConfigTrait;

class ViewListener extends BaseListener
{
    use CrudViewConfigTrait;
    use FormTypeTrait;
    use IndexTypeTrait;
    use SidebarNavigationTrait;
    use SiteTitleTrait;
    use UtilityNavigationTrait;

    /**
     * Default associations config
     *
     * @var array|null
     */
    protected ?array $associations = null;

    /**
     * [beforeFind description]
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     */
    public function beforeFind(EventInterface $event): void
    {
        $related = $this->_getRelatedModels();
        if ($related === []) {
            $this->associations = [];
        } else {
            $this->associations = $this->_associations(array_keys($related));
        }

        if (!$event->getSubject()->query->getContain()) {
            $event->getSubject()->query->contain($related);
        }
    }

    /**
     * [beforePaginate description]
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     */
    public function beforePaginate(EventInterface $event): void
    {
        $related = $this->_getRelatedModels();
        if ($related === []) {
            $this->associations = [];
        } else {
            $this->associations = $this->_associations(array_keys($related));
        }

        if (!$event->getSubject()->query->getContain()) {
            $event->getSubject()->query->contain($this->_getRelatedModels(['manyToOne', 'oneToOne']));
        }
    }

    /**
     * beforeRender event
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     */
    public function beforeRender(EventInterface $event): void
    {
        if ($this->_controller()->getName() === 'Error') {
            return;
        }

        if (!empty($event->getSubject()->entity)) {
            $this->_entity = $event->getSubject()->entity;
        }

        if ($this->associations === null) {
            $this->associations = $this->_associations(array_keys($this->_getRelatedModels()));
        }

        $this->ensureConfig();

        $this->beforeRenderFormType();
        $this->beforeRenderIndexType();
        $this->beforeRenderSiteTitle();
        $this->beforeRenderUtilityNavigation();
        $this->beforeRenderSidebarNavigation();

        $controller = $this->_controller();
        $controller->set('actionConfig', $this->_action()->getConfig());
        $controller->set('title', $this->_getPageTitle());
        $controller->set('breadcrumbs', $this->_getBreadcrumbs());

        $associations = $this->associations;
        $controller->set(compact('associations'));

        $fields = $this->_scaffoldFields($associations);
        $controller->set('fields', $fields);
        $controller->set('formTabGroups', $this->_getFormTabGroups($fields));

        $controller->set('blacklist', $this->_blacklist());
        $controller->set('actions', $this->_getControllerActions());
        $controller->set('bulkActions', $this->_getBulkActions());
        $controller->set('viewblocks', $this->_getViewBlocks());
        $controller->set('actionGroups', $this->_getActionGroups());
        $controller->set($this->_getPageVariables());
    }

    /**
     * Make sure flash messages are properly handled by BootstrapUI.FlashHelper
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     */
    public function setFlash(EventInterface $event): void
    {
        unset($event->getSubject()->params['class']);
        $event->getSubject()->element = ltrim($event->getSubject()->type);
    }

    /**
     * Returns the sites title to show on scaffolded view
     *
     * @return string
     */
    protected function _getPageTitle(): string
    {
        $action = $this->_action();

        $title = $action->getConfig('scaffold.page_title');
        if (!empty($title)) {
            return $title;
        }

        $scope = $action->getConfig('scope');

        $request = $this->_request();
        $actionName = Inflector::humanize(Inflector::underscore($request->getParam('action')));
        $controllerName = $this->_controllerName();

        if ($scope === 'table') {
            if ($actionName === 'Index') {
                return $controllerName;
            }

            return sprintf('%s %s', $controllerName, $actionName);
        }

        $primaryKeyValue = $this->_primaryKeyValue();
        if (empty($primaryKeyValue)) {
            return sprintf('%s %s', $actionName, $controllerName);
        }

        $displayFieldValue = $this->_displayFieldValue();
        if (
            $displayFieldValue === null
            || $this->_table()->getDisplayField() === $this->_table()->getPrimaryKey()
        ) {
            /** @psalm-var string $primaryKeyValue */
            return sprintf('%s %s #%s', $actionName, $controllerName, $primaryKeyValue);
        }

        /** @psalm-var string $primaryKeyValue */
        return sprintf('%s %s #%s: %s', $actionName, $controllerName, $primaryKeyValue, $displayFieldValue);
    }

    /**
     * Get breadcrumns.
     *
     * @return array
     */
    protected function _getBreadcrumbs(): array
    {
        $action = $this->_action();

        return $action->getConfig('scaffold.breadcrumbs') ?: [];
    }

    /**
     * Get a list of relevant models to contain using Containable
     *
     * If the user hasn't configured any relations for an action
     * we assume all relations will be used.
     *
     * The user can choose to suppress specific relations using the blacklist
     * functionality.
     *
     * @param array<string> $associationTypes List of association types.
     * @return array
     */
    protected function _getRelatedModels(array $associationTypes = []): array
    {
        $models = $this->_action()->getConfig('scaffold.relations');

        if ($models === false) {
            return [];
        }

        if (empty($models)) {
            $associations = [];
            if (empty($associationTypes)) {
                $associations = $this->_table()->associations();
            } else {
                foreach ($associationTypes as $assocType) {
                    $associations = array_merge(
                        $associations,
                        $this->_table()->associations()->getByType($assocType)
                    );
                }
            }

            $models = [];
            foreach ($associations as $assoc) {
                $models[] = $assoc->getName();
            }
        }

        $models = Hash::normalize($models);

        $blacklist = $this->_action()->getConfig('scaffold.relations_blacklist');
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
    protected function _blacklist(): array
    {
        return (array)$this->_action()->getConfig('scaffold.fields_blacklist');
    }

    /**
     * Publish fairly static variables needed in the view
     *
     * @return array
     */
    protected function _getPageVariables(): array
    {
        $table = $this->_table();
        $modelClass = $table->getAlias();
        $controller = $this->_controller();
        $scope = $this->_action()->getConfig('scope');

        $data = [
            'modelClass' => $modelClass,
            'singularHumanName' => Inflector::humanize(
                Inflector::underscore(Inflector::singularize($modelClass))
            ),
            'pluralHumanName' => Inflector::humanize(Inflector::underscore($controller->getName())),
            'singularVar' => Inflector::singularize($controller->getName()),
            'pluralVar' => Inflector::variable($controller->getName()),
        ];

        try {
            $data += [
                'modelSchema' => $table->getSchema(),
                'displayField' => $table->getDisplayField(),
                'primaryKey' => $table->getPrimaryKey(),
            ];
        } catch (DatabaseException) {
            // May be empty if there is no table object for the action
        }

        if ($scope === 'entity') {
            $data += [
                'primaryKeyValue' => $this->_primaryKeyValue(),
            ];
        }

        return $data;
    }

    /**
     * Returns fields to be displayed on scaffolded template
     *
     * @param array $associations Associations list.
     * @return array
     */
    protected function _scaffoldFields(array $associations = []): array
    {
        $action = $this->_action();
        $scaffoldFields = (array)$action->getConfig('scaffold.fields');
        if (!empty($scaffoldFields)) {
            $scaffoldFields = Hash::normalize($scaffoldFields);
        }

        if (empty($scaffoldFields) || $action->getConfig('scaffold.autoFields')) {
            $cols = $this->_table()->getSchema()->columns();
            $cols = Hash::normalize($cols);

            $scope = $action->getConfig('scope');
            if ($scope === 'entity' && !empty($associations['manyToMany'])) {
                foreach ($associations['manyToMany'] as $options) {
                    $cols[sprintf('%s._ids', $options['entities'])] = [
                        'multiple' => true,
                    ];
                }
            }

            $scaffoldFields = array_merge($cols, $scaffoldFields);
        }

        // Check for blacklisted fields
        $blacklist = $action->getConfig('scaffold.fields_blacklist');
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

        $fieldSettings = $action->getConfig('scaffold.field_settings');
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
    protected function _controllerName(): string
    {
        $inflections = [
            'underscore',
            'humanize',
        ];

        if ($this->_action()->scope() === 'entity') {
            $inflections[] = 'singularize';
        }

        $baseName = $this->_controller()->getName();
        foreach ($inflections as $inflection) {
            $baseName = Inflector::$inflection($baseName);
        }

        return $baseName;
    }

    /**
     * Returns groupings of action types on the scaffolded view
     * Includes derived actions from scaffold.action_groups
     *
     * @return array
     */
    protected function _getControllerActions(): array
    {
        $table = $entity = [];

        $actions = $this->_getAllowedActions();
        foreach ($actions as $actionName => $config) {
            [$scope, $actionConfig] = $this->_getControllerActionConfiguration($actionName, $config);
            ${$scope}[$actionName] = $actionConfig;
        }

        $actionBlacklist = [];
        $groups = $this->_action()->getConfig('scaffold.action_groups') ?: [];
        foreach ($groups as $group) {
            $group = Hash::normalize($group);
            foreach ($group as $actionName => $config) {
                if (isset($table[$actionName]) || isset($entity[$actionName])) {
                    continue;
                }
                if ($config === null) {
                    $config = [];
                }
                [$scope, $actionConfig] = $this->_getControllerActionConfiguration($actionName, $config);
                $realAction = Hash::get($actionConfig, 'url.action', $actionName);
                if (!isset(${$scope}[$realAction])) {
                    continue;
                }

                $actionBlacklist[] = $realAction;
                ${$scope}[$actionName] = $actionConfig;
            }
        }

        foreach ($actionBlacklist as $actionName) {
            /** @psalm-suppress EmptyArrayAccess */
            unset($table[$actionName]);
            /** @psalm-suppress EmptyArrayAccess */
            unset($entity[$actionName]);
        }

        return compact('table', 'entity');
    }

    /**
     * Returns url action configuration for a given action.
     *
     * This is used to figure out how a given action should be linked to.
     *
     * @param string $actionName Action name.
     * @param array $config Config array.
     * @return array
     */
    protected function _getControllerActionConfiguration(string $actionName, array $config): array
    {
        $realAction = Hash::get($config, 'url.action', $actionName);
        $url = ['action' => $realAction];

        if (isset($config['url'])) {
            $url = $config['url'] + $url;
        }

        if ($this->_crud()->isActionMapped($realAction)) {
            $action = $this->_action($realAction);
            $class = get_class($action);
            /** @psalm-suppress PossiblyFalseOperand */
            $class = substr($class, strrpos($class, '\\') + 1);

            if ($class === 'DeleteAction') {
                $config += ['method' => 'DELETE'];
                $url['?']['_redirect_url'] = $this->_request()->getRequestTarget();
            }

            if (!isset($config['scope'])) {
                $config['scope'] = $class === 'AddAction' ? 'table' : $action->scope();
            }
        }

        // apply defaults if necessary
        $scope = $config['scope'] ?? 'entity';
        $method = $config['method'] ?? 'GET';

        $title = !empty($config['link_title'])
            ? $config['link_title']
            : Inflector::humanize(Inflector::underscore($actionName));

        $actionConfig = [
            'title' => $title,
            'url' => $url,
            'method' => $method,
            'options' => array_diff_key(
                $config,
                array_flip(['method', 'scope', 'link_title', 'url', 'scaffold', 'callback'])
            ),
        ];
        if (!empty($config['callback'])) {
            $actionConfig['callback'] = $config['callback'];
        }

        return [$scope, $actionConfig];
    }

    /**
     * Returns a list of action configs that are allowed to be shown
     *
     * @return array
     */
    protected function _getAllowedActions(): array
    {
        $actions = $this->_action()->getConfig('scaffold.actions');
        if ($actions === null) {
            $actions = array_keys($this->_crud()->getConfig('actions'));
        }

        $extraActions = $this->_action()->getConfig('scaffold.extra_actions') ?: [];

        $allActions = array_merge(
            $this->_normalizeActions($actions),
            $this->_normalizeActions($extraActions)
        );

        $blacklist = (array)$this->_action()->getConfig('scaffold.actions_blacklist');
        $blacklist = array_combine($blacklist, $blacklist);
        foreach ($this->_crud()->getConfig('actions') as $action => $config) {
            if (
                $config['className'] === 'Crud.Lookup' ||
                !$this->_crud()->action($action)->enabled()
            ) {
                $blacklist[$action] = $action;
            }
        }

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
    protected function _normalizeActions(array $actions): array
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
    protected function _associations(array $whitelist = []): array
    {
        $table = $this->_table();

        $associationConfiguration = [];

        $associations = $table->associations();

        $keys = $associations->keys();
        if (!empty($whitelist)) {
            $keys = array_intersect($keys, $whitelist);
        }
        foreach ($keys as $associationName) {
            /** @var \Cake\ORM\Association $association */
            $association = $associations->get($associationName);
            $type = $association->type();

            if (!isset($associationConfiguration[$type])) {
                $associationConfiguration[$type] = [];
            }

            $assocKey = $association->getName();
            $associationConfiguration[$type][$assocKey]['model'] = $assocKey;
            $associationConfiguration[$type][$assocKey]['type'] = $type;
            $associationConfiguration[$type][$assocKey]['primaryKey'] = $association->getTarget()->getPrimaryKey();
            $associationConfiguration[$type][$assocKey]['displayField'] = $association->getTarget()->getDisplayField();
            $associationConfiguration[$type][$assocKey]['foreignKey'] = $association->getForeignKey();
            $associationConfiguration[$type][$assocKey]['propertyName'] = $association->getProperty();
            $associationConfiguration[$type][$assocKey]['plugin'] = pluginSplit($association->getClassName())[0];
            $associationConfiguration[$type][$assocKey]['controller'] = $assocKey;
            $associationConfiguration[$type][$assocKey]['entity'] = Inflector::singularize(
                Inflector::underscore($assocKey)
            );
            $associationConfiguration[$type][$assocKey]['entities'] = Inflector::underscore($assocKey);

            $associationConfiguration[$type][$assocKey] = array_merge(
                $associationConfiguration[$type][$assocKey],
                $this->_action()->getConfig('association.' . $assocKey) ?: []
            );
        }

        return $associationConfiguration;
    }

    /**
     * Derive the Model::primaryKey value from the current context
     *
     * If no value can be found, NULL is returned
     *
     * @return array|string
     */
    protected function _primaryKeyValue(): array|string
    {
        $fields = (array)$this->_table()->getPrimaryKey();
        $values = [];
        foreach ($fields as $field) {
            $values[] = $this->_deriveFieldFromContext($field);
        }

        if (count($values) === 1) {
            return $values[0];
        }

        return $values;
    }

    /**
     * Derive the Model::displayField value from the current context
     *
     * If no value can be found, NULL is returned
     *
     * @return string|int|null
     */
    protected function _displayFieldValue(): string|int|null
    {
        /** @psalm-suppress PossiblyInvalidArgument */
        return $this->_deriveFieldFromContext($this->_table()->getDisplayField());
    }

    /**
     * Extract a field value from a either ServerRequest::getData()
     * or Controller::$viewVars for the current model + the supplied field
     *
     * @param string $field Name of field.
     * @return mixed
     */
    protected function _deriveFieldFromContext(string $field): mixed
    {
        $controller = $this->_controller();
        $modelClass = $this->_table()->getAlias();
        $entity = $this->_entity();
        $request = $this->_request();
        $value = $entity->get($field);

        if ($value) {
            return $value;
        }

        $path = "{$modelClass}.{$field}";
        if (!empty($request->getData())) {
            $value = Hash::get((array)$request->getData(), $path);
        }

        $singularVar = Inflector::variable($modelClass);
        if ($controller->viewBuilder()->getVar($singularVar)) {
            $value = $entity->get($field);
        }

        return $value;
    }

    /**
     * Get view blocks.
     *
     * @return array
     */
    protected function _getViewBlocks(): array
    {
        $action = $this->_action();

        return $action->getConfig('scaffold.viewblocks') ?: [];
    }

    /**
     * Get bulk actions blocks.
     *
     * @return array
     */
    protected function _getBulkActions(): array
    {
        $action = $this->_action();

        return $action->getConfig('scaffold.bulk_actions') ?: [];
    }

    /**
     * Get action groups
     *
     * @return array
     */
    protected function _getActionGroups(): array
    {
        $action = $this->_action();
        $groups = $action->getConfig('scaffold.action_groups') ?: [];

        $groupedActions = [];
        foreach ($groups as $group) {
            $groupedActions[] = array_keys(Hash::normalize($group));
        }

        // add "primary" actions (primary should rendered as separate buttons)
        $groupedActions = (new Collection($groupedActions))->unfold()->toList();
        $groups['primary'] = array_diff(array_keys($this->_getAllowedActions()), $groupedActions);

        return $groups;
    }

    /**
     * Get field tab groups
     *
     * @param array $fields Form fields.
     * @return array
     */
    protected function _getFormTabGroups(array $fields = []): array
    {
        $action = $this->_action();
        /** @var array $groups */
        $groups = $action->getConfig('scaffold.form_tab_groups');

        if (empty($groups)) {
            return [];
        }

        $groupedFields = (new Collection($groups))->unfold()->toList();
        $unGroupedFields = array_diff(array_keys($fields), $groupedFields);

        if (!empty($unGroupedFields)) {
            $primayGroup = $action->getConfig('scaffold.form_primary_tab') ?: __d('crud', 'Primary');

            $groups = [$primayGroup => $unGroupedFields] + $groups;
        }

        return $groups;
    }
}
