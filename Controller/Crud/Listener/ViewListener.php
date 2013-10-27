<?php

App::uses('CrudListener', 'Crud.Controller/Listener');
App::uses('App', 'Core');

class ViewListener extends CrudListener {

/**
 * Initialize the listener
 *
 * @return void
 */
	public function initialize() {
		if ($this->_controller()->name === 'CakeError') {
			return;
		}

		$this->_setViewClass();
		$this->_injectViewSearchPaths();
	}

/**
 * Make sure flash messages uses the views from BoostCake
 *
 * @param CakeEvent $event
 */
	public function setFlash(CakeEvent $event) {
		$event->subject->element = 'alert';
		$event->subject->params['plugin'] = 'BoostCake';
		$event->subject->params['class'] = strpos($event->subject->type, '.success') ? 'alert-success' : 'alert-danger';
	}

/**
 * beforeFind callback
 *
 * Make sure to inject contains for all relations by default
 *
 * @param  CakeEvent $event
 * @return void
 */
	public function beforeFind(CakeEvent $event) {
		$this->_ensureContainableLoaded($event->subject->model);
		$event->subject->query['contain'] = $this->_getRelatedModels();
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
	protected function _getRelatedModels() {
		$models = $this->_action()->config('scaffold.relations');

		if (empty($models)) {
			$associations = $this->_associations();
			$models = [];
			$models += Hash::extract($associations, 'hasMany.{s}.model');
			$models += Hash::extract($associations, 'hasOne.{s}.model');
		}

		$models = Hash::normalize($models);

		// Check for blacklisted fields
		$blacklist = $this->_action()->config('scaffold.relations_blacklist');
		if (!empty($blacklist)) {
			$blacklist = \Hash::normalize($blacklist);
			$models = array_diff_key($models, $blacklist);
		}

		return $models;
	}

/**
 * beforeRender event
 *
 * @param  CakeEvent $event [description]
 * @return void
 */
	public function beforeRender(CakeEvent $event) {
		if ($this->_controller()->name === 'CakeError') {
			return;
		}

		$this->_injectHelpers();
		$this->_prepopulateFormVariables();

		$Controller = $this->_controller();
		$Controller->set('title', $this->_getPageTitle());
		$Controller->set('fields', $this->_getPageFields());
		$Controller->set('actions', $this->_getControllerActions());
		$Controller->set('associations', $this->_associations());
		$Controller->set($this->_getPageVariables());
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
 * Set the view class to use for rendering
 *
 * By default we use cake's build in ScaffoldView as it
 * more or less can do what we need
 *
 * @return void
 */
	protected function _setViewClass() {
		$Controller = $this->_controller();
		$Controller->viewClass = 'Scaffold';
	}

/**
 * Inject the CrudView View path into the views search path
 * so in case the user do not provide their own view, we
 * render our baked in templates first
 *
 * @return void
 */
	protected function _injectViewSearchPaths() {
		App::build(array('View' => array(CakePlugin::path('CrudView') . 'View' . DS)));
	}

/**
 * Publish fairly static variables needed in the view
 *
 * @return array
 */
	protected function _getPageVariables() {
		$data = array(
			'modelClass' => $this->_controller()->modelClass,
			'modelSchema' => $this->_model()->schema(),
			'displayField' => $this->_model()->displayField,
			'singularHumanName' => Inflector::humanize(Inflector::underscore($this->_controller()->modelClass)),
			'pluralHumanName' => Inflector::humanize(Inflector::underscore($this->_controller()->name)),
			'pluralVar' => Inflector::variable($this->_controller()->name),
			'primaryKey' => $this->_model()->primaryKey,
			'primaryKeyValue' => $this->_primaryKeyValue()
		);

		if (method_exists($this->_action(), 'viewVar')) {
			$data['viewVar'] = $this->_action()->viewVar();
		}

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
			return sprintf('%s %s', $actionName, $controllerName);
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
		$Model = $this->_model();

		// Get all available fields from the Schema
		$modelSchema = $Model->schema();

		// Make the fields
		$scaffoldFields = array_fill_keys(array_keys($modelSchema), array());

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
		$type = $CrudAction::ACTION_SCOPE;

		$baseName = Inflector::humanize(Inflector::underscore($Controller->name));

		if ($type === CrudAction::SCOPE_MODEL) {
			return Inflector::pluralize($baseName);
		}

		if ($type === CrudAction::SCOPE_RECORD) {
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
		$actions = $this->_crud()->config('actions');

		$model = array();
		$record = array();
		foreach ($actions as $actionName => $config) {
			$action = $this->_action($actionName);
			$type = $action::ACTION_SCOPE;

			if ($type === CrudAction::SCOPE_MODEL) {
				$model[] = $actionName;
			} elseif ($type === CrudAction::SCOPE_RECORD) {
				$record[] = $actionName;
			}
		}

		return compact('model', 'record');
	}

/**
 * Returns associations for controllers models.
 *
 * @return array Associations for model
 */
	protected function _associations() {
		$model = $this->_model();
		$associations = array();

		$associated = $model->getAssociated();
		foreach ($associated as $assocKey => $type) {
			if (!isset($associations[$type])) {
				$associations[$type] = array();
			}

			$assocDataAll = $model->{$type};

			$assocData = $assocDataAll[$assocKey];
			$associatedModel = $model->{$assocKey};

			$associations[$type][$assocKey]['model'] = $assocKey;
			$associations[$type][$assocKey]['type'] = $type;
			$associations[$type][$assocKey]['primaryKey'] = $associatedModel->primaryKey;
			$associations[$type][$assocKey]['displayField'] = $associatedModel->displayField;
			$associations[$type][$assocKey]['foreignKey'] = $assocData['foreignKey'];

			list($plugin, $modelClass) = pluginSplit($assocData['className']);

			if ($plugin) {
				$plugin = Inflector::underscore($plugin);
			}

			$associations[$type][$assocKey]['plugin'] = $plugin;
			$associations[$type][$assocKey]['controller'] = Inflector::pluralize(Inflector::underscore($modelClass));

			if ($type === 'hasAndBelongsToMany') {
				$associations[$type][$assocKey]['with'] = $assocData['with'];
			}
		}

		if (empty($associations['hasMany'])) {
			$associations['hasMany'] = array();
		}

		if (empty($associations['hasAndBelongsToMany'])) {
			$associations['hasAndBelongsToMany'] = array();
		}

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
		return $this->_derriveFieldFromContext($this->_model()->primaryKey);
	}

/**
 * Derive the Model::displayField value from the current context
 *
 * If no value can be found, NULL is returned
 *
 * @return string
 */
	protected function _displayFieldValue() {
		return $this->_derriveFieldFromContext($this->_model()->displayField);
	}

/**
 * Extract a field value from a either the CakeRequest::$data
 * or Controller::$viewVars for the current model + the supplied field
 *
 * @param  string $field
 * @return mixed
 */
	protected function _derriveFieldFromContext($field) {
		$controller = $this->_controller();
		$model = $this->_model();
		$request = $this->_request();
		$value = null;

		$path = "{$controller->modelClass}.{$field}";
		if (!empty($request->data)) {
			$value = Hash::get($request->data, $path);
		}

		$singularVar = Inflector::variable($controller->modelClass);
		if (!empty($controller->viewVars[$singularVar])) {
			$value = Hash::get($controller->viewVars[$singularVar], $path);
		}

		return $value;
	}

/**
 * Make sure containable behavior is loaded for a model
 *
 * @param  Model  $model
 * @return void
 */
	protected function _ensureContainableLoaded(Model $model) {
		$model->Behaviors->load('Containable');
	}

}
