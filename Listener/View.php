<?php
namespace CrudView\Listener;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Crud\Listener\Base;

class View extends Base {

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
	public function setFlash(\Cake\Event\Event $event) {
		$event->subject->params['plugin'] = 'BoostCake';
		$event->subject->params['class'] = 'alert alert-dismissable ';
		$event->subject->params['class'] .= strpos($event->subject->type, '.success') ? 'alert-success' : 'alert-danger';
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
			$blacklist = \Hash::normalize($blacklist);
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
	public function beforeRender(\Cake\Event\Event $event) {
		if ($this->_controller()->name === 'CakeError') {
			return;
		}

		if (!empty($event->subject->entity)) {
			$this->_entity = $event->subject->entity;
		}

		$this->_injectHelpers();
		$this->_prepopulateFormVariables();

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
 * Copy GET arguments into the form data
 *
 * Useful for deep linking variables for related models
 *
 * @return void
 */
	protected function _prepopulateFormVariables() {
		$request = $this->_request();

		if (!$request->is('get') || empty($request->query)) {
			return;
		}

		$modelClass = $this->_controller()->modelClass;
		if (empty($request->data[$modelClass])) {
			$request->data[$modelClass] = array();
		}

		$request->data[$modelClass] = array_merge($request->query, $request->data[$modelClass]);
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
		$data = array(
			'modelClass' => $this->_controller()->modelClass,
			'modelSchema' => $this->_repository()->schema(),
			'displayField' => $this->_repository()->displayField(),
			'singularHumanName' => Inflector::humanize(Inflector::underscore(Inflector::singularize($this->_controller()->modelClass))),
			'pluralHumanName' => Inflector::humanize(Inflector::underscore($this->_controller()->name)),
			'singularVar' => Inflector::singularize($this->_controller()->name),
			'pluralVar' => Inflector::variable($this->_controller()->name),
			'primaryKey' => $this->_repository()->primaryKey(),
			'primaryKeyValue' => $this->_primaryKeyValue()
		);

		// if (method_exists($this->_action(), 'viewVar')) {
		// 	$data['viewVar'] = $this->_action()->viewVar();
		// }

		return $data;
	}

/**
 * Returns the page title to show on scaffolded view
 *
 * @return string
 */
	protected function _getPageTitle() {
		$title = $this->_action()->config('scaffold.page_title');
		if (!empty($title)) {
			return $title;
		}

		$request = $this->_request();
		$actionName = Inflector::humanize(Inflector::underscore($request->action));
		$controllerName = $this->_controllerName();

		$primaryKeyValue = $this->_primaryKeyValue();
		$displayFieldValue = $this->_displayFieldValue();

		if ($primaryKeyValue === null && $displayFieldValue === null) {
			if ($actionName === 'Index') {
				return $controllerName;
			}

			return sprintf('%s %s', $controllerName, $actionName);
		}

		if ($displayFieldValue === null) {
			return sprintf('%s %s #%s', $actionName, $controllerName, $primaryKeyValue);
		}

		if ($primaryKeyValue === null) {
			return sprintf('%s %s %s', $actionName, $controllerName, $displayFieldValue);
		}

		return sprintf('%s %s #%s: %s', $actionName, $controllerName, $primaryKeyValue, $displayFieldValue);
	}

/**
 * Get fields that should be visible in the view
 *
 * @return array
 */
	protected function _getPageFields() {
		return $this->_scaffoldFields();
	}

/**
 * Returns fields to be displayed on scaffolded view
 *
 * @param boolean $sort Add sort keys to output
 * @return array List of fields
 */
	protected function _scaffoldFields() {
		$Model = $this->_repository();

		// Get all available fields from the Schema
		$modelSchema = $Model->schema();

		// Make the fields
		$cols = $modelSchema->columns();
		$scaffoldFields = array_combine(array_values($cols), array_fill(0, sizeof($cols), []));

		// Check for any user configured fields
		$configuredFields = $this->_action()->config('scaffold.fields');
		if (!empty($configuredFields)) {
			$configuredFields = Hash::normalize($configuredFields);
			$scaffoldFields = array_intersect_key($configuredFields, $scaffoldFields);
		}

		// Check for blacklisted fields
		$blacklist = $this->_action()->config('scaffold.fields_blacklist');
		if (!empty($blacklist)) {
			$scaffoldFields = array_diff_key($scaffoldFields, array_combine($blacklist, $blacklist));
		}

		// Make sure all array values are an array
		foreach ($scaffoldFields as $field => $options) {
			if (!is_array($options)) {
				$scaffoldFields[$field] = (array)$options;
			}

			$scaffoldFields[$field] += array(
				'formatter' => null
			);
		}

		return $scaffoldFields;
	}

/**
 * Get the controller name based on the CrudAction scope
 *
 * @return string
 */
	protected function _controllerName() {
		$Controller = $this->_controller();
		$CrudAction = $this->_action();

		$baseName = Inflector::humanize(Inflector::underscore($Controller->name));

		if ($CrudAction->scope() === 'repository') {
			return Inflector::pluralize($baseName);
		}

		if ($CrudAction->scope() === 'entity') {
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
		$model = $this->_repository();
		$associations = [
			'oneToOne' => [],
			'oneToMany' => [],
			'hasAndBelongsToMany' => []
		];

		foreach ($model->associations()->keys() as $associationName) {
			$association = $model->associations()->get($associationName);

			$type = $association->type();

			if (!isset($associations[$type])) {
				$associations[$type] = [];
			}

			$assocKey = Inflector::variable(Inflector::underscore($association->name()));
			$associations[$type][$assocKey]['model'] = $assocKey;
			$associations[$type][$assocKey]['type'] = $type;
			$associations[$type][$assocKey]['primaryKey'] = $association->target()->primaryKey();
			$associations[$type][$assocKey]['displayField'] = $association->target()->displayField();
			$associations[$type][$assocKey]['foreignKey'] = $association->foreignKey();

			// list($plugin, $modelClass) = pluginSplit($assocData['className']);

			// if ($plugin) {
			// 	$plugin = Inflector::underscore($plugin);
			// }

			$associations[$type][$assocKey]['plugin'] = null;
			$associations[$type][$assocKey]['controller'] = Inflector::pluralize(Inflector::underscore($assocKey));

			// if ($type === 'hasAndBelongsToMany') {
			// 	$associations[$type][$assocKey]['with'] = $assocData['with'];
			// }
		}

		// debug($associations);die;
		return $associations;
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
		return $this->_deriveFieldFromContext($this->_repository()->displayField());
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
