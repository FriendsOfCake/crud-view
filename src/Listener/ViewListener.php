<?php
namespace CrudView\Listener;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Crud\Listener\BaseListener;

class ViewListener extends BaseListener
{
    /**
     * Initialize the listener
     *
     * @return void
     */
    public function initialize()
    {
        if ($this->_controller()->name === 'CakeError') {
            return;
        }

        $this->_injectViewSearchPaths();
    }

    /**
     * [beforeFind description]
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforeFind(Event $event)
    {
        $event->subject->query->contain($this->_getRelatedModels());
    }

    /**
     * [beforePaginate description]
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function beforePaginate(Event $event)
    {
        if (!$event->subject->object) {
            $event->subject->object = $this->_table()->query();
        }

        $event->subject->object->contain($this->_getRelatedModels());
    }

    /**
     * Make sure flash messages uses the views from BoostCake
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function setFlash(Event $event)
    {
        $event->subject->params['class'] = 'alert alert-dismissable ';
        $event->subject->params['class'] .= (false !== strpos($event->subject->type, '.success')) ? 'alert-success' : 'alert-danger';
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

        if (!empty($event->subject->entity)) {
            $this->_entity = $event->subject->entity;
        }

        $this->_injectHelpers();

        $controller = $this->_controller();
        $controller->set('title', $this->_getPageTitle());
        $controller->set('fields', $this->_scaffoldFields());
        $controller->set('blacklist', $this->_blacklist());
        $controller->set('actions', $this->_getControllerActions());
        $controller->set('associations', $this->_associations());
        $controller->set('tables', $this->_getTables());
        $controller->set('bulkActions', $this->_getBulkActions());
        $controller->set('viewblocks', $this->_getViewBlocks());
        $controller->set($this->_getPageVariables());
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
     * Inject helpers required for the frontend
     *
     * @return void
     */
    protected function _injectHelpers()
    {
        $Controller = $this->_controller();
        $Controller->helpers[] = 'CrudView.CrudView';
    }

    /**
     * Inject the CrudView View path into the views search path
     * so in case the user do not provide their own view, we
     * render our baked in templates first
     *
     * @return void
     */
    protected function _injectViewSearchPaths()
    {
        $existing = Configure::read('App.paths.templates');
        $existing[] = Plugin::path('CrudView') . 'Template' . DS;

        Configure::write('App.paths.templates', $existing);
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
        $displayFieldValue = $this->_displayFieldValue();

        if ($displayFieldValue === null) {
            return sprintf('%s %s #%s', $actionName, $controllerName, $primaryKeyValue);
        }

        if ($primaryKeyValue === null) {
            return sprintf('%s %s %s', $actionName, $controllerName, $displayFieldValue);
        }

        return sprintf('%s %s #%s: %s', $actionName, $controllerName, $primaryKeyValue, $displayFieldValue);
    }

    /**
     * Returns fields to be displayed on scaffolded template
     *
     * @return array
     */
    protected function _scaffoldFields()
    {
        $cols = $this->_table()->schema()->columns();
        $scaffoldFields = array_combine(array_values($cols), array_fill(0, count($cols), []));

        $action = $this->_action();
        $configuredFields = $action->config('scaffold.fields');
        if (!empty($configuredFields)) {
            $configuredFields = Hash::normalize($configuredFields);
            $scaffoldFields = array_intersect_key($configuredFields, $scaffoldFields);
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

        return $scaffoldFields;
    }

    /**
     * Get the controller name based on the Crud Action scope
     *
     * @return string
     */
    protected function _controllerName()
    {
        $baseName = Inflector::humanize(Inflector::underscore($this->_controller()->name));

        if ($this->_action()->scope() === 'table') {
            return Inflector::pluralize($baseName);
        }

        if ($this->_action()->scope() === 'entity') {
            return Inflector::singularize($baseName);
        }

        return $baseName;
    }

    /**
     * Returns groupings of action types on the scaffolded view
     *
     * @return string
     */
    protected function _getControllerActions()
    {
        $table = $entity = [];

        $actions = $this->_crud()->config('actions');
        $blacklist = (array)$this->_action()->config('scaffold.actions_blacklist');
        $blacklist = array_combine($blacklist, $blacklist);
        $actions = array_diff_key($actions, $blacklist);

        foreach ($actions as $actionName => $config) {
            $action = $this->_action($actionName);

            if ($action->scope() === 'table') {
                $table[$actionName] = [
                    'title' => Inflector::humanize($actionName),
                    'controller' => $this->_request()->params['controller'],
                ];
            } elseif ($action->scope() === 'entity') {
                $entity[$actionName] = [
                    'title' => Inflector::humanize($actionName),
                    'controller' => $this->_request()->params['controller'],
                ];
            }

        }

        return compact('table', 'entity');
    }

    /**
     * Returns associations for controllers models.
     *
     * @return array Associations for model
     */
    protected function _associations()
    {
        $table = $this->_table();

        $associationConfiguration = [];

        $associations = $table->associations();

        foreach ($associations->keys() as $associationName) {
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
            $associationConfiguration[$type][$assocKey]['plugin'] = null;
            $associationConfiguration[$type][$assocKey]['controller'] = Inflector::pluralize(Inflector::underscore($assocKey));
            $associationConfiguration[$type][$assocKey]['entity'] = Inflector::singularize(Inflector::underscore($assocKey));
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
        $value = null;

        if ($value = $entity->get($field)) {
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

    protected function _getTables()
    {
        $action = $this->_action();
        $tables = $action->config('scaffold.tables');
        if (empty($tables)) {
            $connection = ConnectionManager::get('default');
            $schema = $connection->schemaCollection();
            $tables = $schema->listTables();
            ksort($tables);

            $blacklist = $action->config('scaffold.tables_blacklist');
            if (!empty($blacklist)) {
                $tables = array_diff($tables, $blacklist);
            }
        }

        $normal = [];
        foreach ($tables as $table => $config) {
            if (is_string($config)) {
                $config = ['table' => $config];
            }

            if (is_int($table)) {
                $table = $config['table'];
            }

            if (empty($config['action'])) {
                $config['action'] = 'index';
            }

            if (empty($config['title'])) {
                $config['title'] = Inflector::humanize($table);
            }

            if (empty($config['controller'])) {
                $config['controller'] = Inflector::camelize($table);
            }

            $normal[$table] = $config;
        }

        return $normal;
    }

    protected function _getViewBlocks()
    {
        $action = $this->_action();
        $viewblocks = $action->config('scaffold.viewblocks');
        if (empty($viewblocks)) {
            $viewblocks = [];
        }

        return $viewblocks;
    }

    protected function _getBulkActions()
    {
        $action = $this->_action();
        $bulkActions = $action->config('scaffold.bulk_actions');
        if (empty($bulkActions)) {
            $bulkActions = [];
        }

        return $bulkActions;
    }
}
