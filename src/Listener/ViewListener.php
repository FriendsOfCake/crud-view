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
use function Cake\Core\pluginSplit;
use function Cake\I18n\__d;

/**
 * @method \Cake\ORM\Table model()
 */
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
     * @var array
     */
    protected array $associations;

    /**
     * [beforeFind description]
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     * @phpstan-param \Cake\Event\EventInterface<\Crud\Event\Subject> $event
     */
    public function beforeFind(EventInterface $event): void
    {
        $related = $this->getRelatedModels();
        if ($related === []) {
            $this->associations = [];
        } else {
            $this->associations = $this->associations(array_keys($related));
        }

        $subject = $event->getSubject();
        if ($subject === null) {
            return;
        }

        if (!$subject->query->getContain()) {
            $subject->query->contain($related);
        }
    }

    /**
     * [beforePaginate description]
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     * @phpstan-param \Cake\Event\EventInterface<\Crud\Event\Subject> $event
     */
    public function beforePaginate(EventInterface $event): void
    {
        $related = $this->getRelatedModels();
        if ($related === []) {
            $this->associations = [];
        } else {
            $this->associations = $this->associations(array_keys($related));
        }

        $subject = $event->getSubject();
        if ($subject === null) {
            return;
        }

        if (!$subject->query->getContain()) {
            $subject->query->contain($this->getRelatedModels(['belongsTo', 'hasOne']));
        }
    }

    /**
     * beforeRender event
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     * @phpstan-param \Cake\Event\EventInterface<\Crud\Event\Subject> $event
     */
    public function beforeRender(EventInterface $event): void
    {
        if ($this->controller()->getName() === 'Error') {
            return;
        }

        $subject = $event->getSubject();
        if ($subject !== null && !empty($subject->entity)) {
            $this->entity = $subject->entity;
        }

        if (!isset($this->associations)) {
            $this->associations = $this->associations(array_keys($this->getRelatedModels()));
        }

        $this->ensureConfig();

        $this->beforeRenderFormType();
        $this->beforeRenderIndexType();
        $this->beforeRenderSiteTitle();
        $this->beforeRenderUtilityNavigation();
        $this->beforeRenderSidebarNavigation();

        $controller = $this->controller();
        $controller->set('actionConfig', $this->action()->getConfig());
        $controller->set('title', $this->getPageTitle());
        $controller->set('breadcrumbs', $this->getBreadcrumbs());

        $associations = $this->associations;
        $controller->set(compact('associations'));

        $fields = $this->scaffoldFields($associations);
        $controller->set('fields', $fields);
        $controller->set('formTabGroups', $this->getFormTabGroups($fields));

        $controller->set('blacklist', $this->blacklist());
        $controller->set('actions', $this->getControllerActions());
        $controller->set('bulkActions', $this->getBulkActions());
        $controller->set('viewblocks', $this->getViewBlocks());
        $controller->set('actionGroups', $this->getActionGroups());
        $controller->set($this->getPageVariables());
    }

    /**
     * Make sure flash messages are properly handled by BootstrapUI.FlashHelper
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     * @phpstan-param \Cake\Event\EventInterface<\Crud\Event\Subject> $event
     */
    public function setFlash(EventInterface $event): void
    {
        $subject = $event->getSubject();
        if ($subject === null) {
            return;
        }

        unset($subject->params['class']);
        $subject->element = ltrim($subject->type);
    }

    /**
     * Returns the sites title to show on scaffolded view
     *
     * @return string
     */
    protected function getPageTitle(): string
    {
        $action = $this->action();

        $title = $action->getConfig('scaffold.page_title');
        if (!empty($title)) {
            return $title;
        }

        $scope = $action->getConfig('scope');

        $request = $this->request();
        $actionName = Inflector::humanize(Inflector::underscore($request->getParam('action')));
        $controllerName = $this->controllerName();

        if ($scope === 'table') {
            if ($actionName === 'Index') {
                return $controllerName;
            }

            return sprintf('%s %s', $controllerName, $actionName);
        }

        $primaryKeyValue = $this->primaryKeyValue();
        if ($primaryKeyValue === null) {
            return sprintf('%s %s', $actionName, $controllerName);
        }

        $displayFieldValue = $this->displayFieldValue();
        if (
            $displayFieldValue === null
            || $this->model()->getDisplayField() === $this->model()->getPrimaryKey()
        ) {
            /** @phpstan-var string $primaryKeyValue */
            return sprintf('%s %s #%s', $actionName, $controllerName, $primaryKeyValue);
        }

        /** @phpstan-var string $primaryKeyValue */
        return sprintf('%s %s #%s: %s', $actionName, $controllerName, $primaryKeyValue, $displayFieldValue);
    }

    /**
     * Get breadcrumns.
     *
     * @return array
     */
    protected function getBreadcrumbs(): array
    {
        $action = $this->action();

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
    protected function getRelatedModels(array $associationTypes = []): array
    {
        $models = $this->action()->getConfig('scaffold.relations');

        if ($models === false) {
            return [];
        }

        if (empty($models)) {
            $associations = [];
            if (empty($associationTypes)) {
                $associations = $this->model()->associations();
            } else {
                foreach ($associationTypes as $assocType) {
                    $associations = array_merge(
                        $associations,
                        $this->model()->associations()->getByType($assocType),
                    );
                }
            }

            $models = [];
            foreach ($associations as $assoc) {
                $models[] = $assoc->getName();
            }
        }

        $blacklist = $this->action()->getConfig('scaffold.relations_blacklist');
        if (!empty($blacklist)) {
            $models = array_diff($models, $blacklist);
        }

        return $models;
    }

    /**
     * Get list of blacklisted fields from config.
     *
     * @return array
     */
    protected function blacklist(): array
    {
        return (array)$this->action()->getConfig('scaffold.fields_blacklist');
    }

    /**
     * Publish fairly static variables needed in the view
     *
     * @return array
     */
    protected function getPageVariables(): array
    {
        $table = $this->model();
        $modelClass = $table->getAlias();
        $controller = $this->controller();
        $scope = $this->action()->getConfig('scope');

        $data = [
            'modelClass' => $modelClass,
            'singularHumanName' => Inflector::humanize(
                Inflector::underscore(Inflector::singularize($modelClass)),
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
                'primaryKeyValue' => $this->primaryKeyValue(),
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
    protected function scaffoldFields(array $associations = []): array
    {
        $action = $this->action();
        $scaffoldFields = Hash::normalize(
            (array)$action->getConfig('scaffold.fields'),
            default: [],
        );

        if (empty($scaffoldFields) || $action->getConfig('scaffold.autoFields')) {
            $cols = $this->model()->getSchema()->columns();
            $cols = Hash::normalize($cols, default: []);

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
        $blacklist = $this->blacklist();
        if ($blacklist) {
            $scaffoldFields = array_diff_key($scaffoldFields, array_combine($blacklist, $blacklist));
        }

        // Make sure all array values are an array
        foreach ($scaffoldFields as $field => $options) {
            $scaffoldFields[$field] += ['formatter' => null];
        }

        $fieldSettings = Hash::normalize(
            (array)$action->getConfig('scaffold.field_settings'),
            default: [],
        );
        $fieldSettings = array_intersect_key($fieldSettings, $scaffoldFields);
        $scaffoldFields = Hash::merge($scaffoldFields, $fieldSettings);

        return $scaffoldFields;
    }

    /**
     * Get the controller name based on the Crud Action scope
     *
     * @return string
     */
    protected function controllerName(): string
    {
        $inflections = [
            'underscore',
            'humanize',
        ];

        if ($this->action()->scope() === 'entity') {
            $inflections[] = 'singularize';
        }

        $baseName = $this->controller()->getName();
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
    protected function getControllerActions(): array
    {
        $table = $entity = [];

        $actions = $this->getAllowedActions();
        foreach ($actions as $actionName => $config) {
            [$scope, $actionConfig] = $this->getControllerActionConfiguration($actionName, $config);
            ${$scope}[$actionName] = $actionConfig;
        }

        $actionBlacklist = [];
        $groups = $this->action()->getConfig('scaffold.action_groups') ?: [];
        foreach ($groups as $group) {
            $group = Hash::normalize($group, default: []);
            foreach ($group as $actionName => $config) {
                if (isset($table[$actionName]) || isset($entity[$actionName])) {
                    continue;
                }
                [$scope, $actionConfig] = $this->getControllerActionConfiguration($actionName, $config);
                $realAction = Hash::get($actionConfig, 'url.action', $actionName);
                if (!isset(${$scope}[$realAction])) {
                    continue;
                }

                $actionBlacklist[] = $realAction;
                ${$scope}[$actionName] = $actionConfig;
            }
        }

        foreach ($actionBlacklist as $actionName) {
            unset($table[$actionName]);
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
    protected function getControllerActionConfiguration(string $actionName, array $config): array
    {
        $realAction = Hash::get($config, 'url.action', $actionName);
        $url = ['action' => $realAction];

        if (isset($config['url'])) {
            $url = $config['url'] + $url;
        }

        if ($this->crud()->isActionMapped($realAction)) {
            $action = $this->action($realAction);
            $class = get_class($action);
            $class = substr($class, strrpos($class, '\\') + 1);

            if ($class === 'DeleteAction') {
                $config += ['method' => 'DELETE'];
                $url['?']['_redirect_url'] = $this->request()->getRequestTarget();
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
                array_flip(['method', 'scope', 'link_title', 'url', 'scaffold', 'callback']),
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
    protected function getAllowedActions(): array
    {
        $actions = $this->action()->getConfig('scaffold.actions');
        if ($actions === null) {
            $actions = array_keys($this->crud()->getConfig('actions'));
        }

        $extraActions = $this->action()->getConfig('scaffold.extra_actions') ?: [];

        $allActions = array_merge(
            Hash::normalize($actions, default: []),
            Hash::normalize($extraActions, default: []),
        );

        $blacklist = (array)$this->action()->getConfig('scaffold.actions_blacklist');
        $blacklist = array_combine($blacklist, $blacklist);
        foreach ($this->crud()->getConfig('actions') as $action => $config) {
            if (
                $config['className'] === 'Crud.Lookup' ||
                !$this->crud()->action($action)->enabled()
            ) {
                $blacklist[$action] = $action;
            }
        }

        return array_diff_key($allActions, $blacklist);
    }

    /**
     * Returns associations for controllers models.
     *
     * @param array $whitelist Whitelist of associations to return.
     * @return array Associations for model
     */
    protected function associations(array $whitelist = []): array
    {
        $table = $this->model();

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
                Inflector::underscore($assocKey),
            );
            $associationConfiguration[$type][$assocKey]['entities'] = Inflector::underscore($assocKey);

            $associationConfiguration[$type][$assocKey] = array_merge(
                $associationConfiguration[$type][$assocKey],
                $this->action()->getConfig('association.' . $assocKey) ?: [],
            );
        }

        return $associationConfiguration;
    }

    /**
     * Derive the Model::primaryKey value from the current context
     *
     * If no value can be found, NULL is returned
     *
     * @return array|string|int|null
     */
    protected function primaryKeyValue(): array|string|int|null
    {
        $fields = (array)$this->model()->getPrimaryKey();
        $values = [];
        foreach ($fields as $field) {
            $values[] = $this->deriveFieldFromContext($field);
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
    protected function displayFieldValue(): string|int|null
    {
        $displayField = $this->model()->getDisplayField();
        if (is_array($displayField)) {
            $displayField = (string)array_key_first($displayField);
        }

        return $this->deriveFieldFromContext($displayField);
    }

    /**
     * Extract a field value from a either ServerRequest::getData()
     * or Controller::$viewVars for the current model + the supplied field
     *
     * @param string $field Name of field.
     * @return mixed
     */
    protected function deriveFieldFromContext(string $field): mixed
    {
        $controller = $this->controller();
        $modelClass = $this->model()->getAlias();
        $entity = $this->entity();
        $request = $this->request();
        $value = $entity->get($field);

        if ($value !== null) {
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
    protected function getViewBlocks(): array
    {
        $action = $this->action();

        return $action->getConfig('scaffold.viewblocks') ?: [];
    }

    /**
     * Get bulk actions blocks.
     *
     * @return array
     */
    protected function getBulkActions(): array
    {
        $action = $this->action();

        return $action->getConfig('scaffold.bulk_actions') ?: [];
    }

    /**
     * Get action groups
     *
     * @return array
     */
    protected function getActionGroups(): array
    {
        $action = $this->action();
        $groups = $action->getConfig('scaffold.action_groups') ?: [];

        $groupedActions = [];
        foreach ($groups as $group) {
            $groupedActions[] = array_keys(Hash::normalize($group));
        }

        // add "primary" actions (primary should rendered as separate buttons)
        $groupedActions = (new Collection($groupedActions))->unfold()->toList();
        $groups['primary'] = array_diff(array_keys($this->getAllowedActions()), $groupedActions);

        return $groups;
    }

    /**
     * Get field tab groups
     *
     * @param array $fields Form fields.
     * @return array
     */
    protected function getFormTabGroups(array $fields = []): array
    {
        $action = $this->action();
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
