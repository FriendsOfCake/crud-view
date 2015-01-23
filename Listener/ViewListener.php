<?php
namespace CrudView\Listener;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Crud\Listener\BaseListener;

class ViewListener extends BaseListener {

/**
 * Initialize the listener
 *
 * @return void
 */
	public function initialize() {
		if ($this->_controller()->name === 'CakeError') {
			return;
		}

		$this->_injectViewSearchPaths();
	}

	public function beforeFind(Event $event) {
		$event->subject->query->contain($this->_getRelatedModels());
	}

/**
 * Make sure flash messages uses the views from BoostCake
 *
 * @param CakeEvent $event
 */
	public function setFlash(Event $event) {
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
 * @return array
 */
	protected function _getRelatedModels($relations = []) {
		$models = $this->_action()->config('scaffold.relations');

		if (empty($models)) {
			$associations = $this->_associations();

			if (empty($relations)) {
				$relations = array_keys($associations);
			}

			$models = [];
			foreach ($relations as $relation) {
				$models += (array)Hash::extract($associations, "$relation.{s}.model");
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
 * @param  CakeEvent $event [description]
 * @return void
 */
	public function beforeRender(Event $event) {
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
		$controller->set($this->_getPageVariables());
	}

	protected function _blacklist() {
		return (array)$this->_action()->config('scaffold.fields_blacklist');
	}

/**
 * Inject helpers required for the frontend
 *
 * @return void
 */
	protected function _injectHelpers() {
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
	protected function _injectViewSearchPaths() {
		$existing = Configure::read('App.paths.templates');
		$existing[] = Plugin::path('CrudView') . 'Template' . DS;

		Configure::write('App.paths.templates', $existing);
	}

/**
 * Publish fairly static variables needed in the view
 *
 * @return array
 */
	protected function _getPageVariables() {
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
	protected function _getPageTitle() {
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
	protected function _scaffoldFields() {
		$cols = $this->_table()->schema()->columns();
		$scaffoldFields = array_combine(array_values($cols), array_fill(0, sizeof($cols), []));

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
	protected function _controllerName() {
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
	protected function _getControllerActions() {
		$table = $entity = [];

		$actions = $this->_crud()->config('actions');
		foreach ($actions as $actionName => $config) {
			$action = $this->_action($actionName);

			if ($action->scope() === 'table') {
				$table[] = $actionName;
			} elseif ($action->scope() === 'entity') {
				$entity[] = $actionName;
			}

		}

		return compact('table', 'entity');
	}

/**
 * Returns associations for controllers models.
 *
 * @return array Associations for model
 */
	protected function _associations() {
		$table = $this->_table();

		$associationConfiguration = [];

		$associations = $table->associations();
		foreach ($associations->keys() as $associationName) {
			$association = $associations->get($associationName);
			$type = $association->type();

			if (!isset($associationConfiguration[$type])) {
				$associationConfiguration[$type] = [];
			}

			$assocKey = Inflector::variable(Inflector::underscore($association->name()));
			$associationConfiguration[$type][$assocKey]['model'] = $assocKey;
			$associationConfiguration[$type][$assocKey]['type'] = $type;
			$associationConfiguration[$type][$assocKey]['primaryKey'] = $association->target()->primaryKey();
			$associationConfiguration[$type][$assocKey]['displayField'] = $association->target()->displayField();
			$associationConfiguration[$type][$assocKey]['foreignKey'] = $association->foreignKey();
			$associationConfiguration[$type][$assocKey]['plugin'] = null;
			$associationConfiguration[$type][$assocKey]['controller'] = Inflector::pluralize(Inflector::underscore($assocKey));
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
	protected function _primaryKeyValue() {
		return $this->_deriveFieldFromContext($this->_table()->primaryKey());
	}

/**
 * Derive the Model::displayField value from the current context
 *
 * If no value can be found, NULL is returned
 *
 * @return string
 */
	protected function _displayFieldValue() {
		return $this->_deriveFieldFromContext($this->_table()->displayField());
	}

/**
 * Extract a field value from a either the CakeRequest::$data
 * or Controller::$viewVars for the current model + the supplied field
 *
 * @param  string $field
 * @return mixed
 */
	protected function _deriveFieldFromContext($field) {
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

}
