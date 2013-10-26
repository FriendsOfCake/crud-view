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
		$scaffoldTitle = $this->_action()->config('scaffold.page_title');

		if (!empty($scaffoldTitle)) {
			return $scaffoldTitle;
		}

		$request = $this->_request();
		$actionName = Inflector::humanize(Inflector::underscore($request->action));
		$controllerName = $this->_controllerName();

		$primaryKeyValue = $this->_primaryKeyValue();
		$displayFieldValue = $this->_displayFieldValue();

		if ($primaryKeyValue === null && $displayFieldValue === null) {
			$scaffoldTitle = sprintf('%s %s', $actionName, $controllerName);
		} elseif ($displayFieldValue === null) {
			$scaffoldTitle = sprintf('%s %s #%s', $actionName, $controllerName, $primaryKeyValue);
		} elseif ($primaryKeyValue === null) {
			$scaffoldTitle = sprintf('%s %s %s', $actionName, $controllerName, $displayFieldValue);
		} else {
			$scaffoldTitle = sprintf('%s %s #%s: %s', $actionName, $controllerName, $primaryKeyValue, $displayFieldValue);
		}

		return $scaffoldTitle;
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
		$scaffoldFields = array_fill_keys(array_keys($modelSchema), array());

		// Check for any user configured fields
		$configuredFields = $this->_action()->config('scaffold.fields');
		if (!empty($configuredFields)) {
			$configuredFields = Hash::normalize($configuredFields);
			$scaffoldFields = array_intersect_key($scaffoldFields, $configuredFields);
		}

		// Check for blacklisted fields
		$blacklist = $this->_action()->config('scaffold.field_blacklist');
		if (!empty($blacklist)) {
			$scaffoldFields = array_diff_key($scaffoldFields, array_combine($blacklist, $blacklist));
		}

		return $scaffoldFields;
	}

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

			$assocDataAll = $model->$type;

			$assocData = $assocDataAll[$assocKey];
			$associatedModel = $model->{$assocKey};

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

	protected function _primaryKeyValue() {
		$controller = $this->_controller();
		$request = $this->_request();
	    $model = $this->_model();
	    $primaryKeyValue = null;
	    $path = null;

	    if (!empty($controller->modelClass) && !empty($model->primaryKey)) {
	      $path = "{$controller->modelClass}.{$model->primaryKey}";
	      if (!empty($request->data)) {
	        $primaryKeyValue = Hash::get($request->data, $path);
	      }

	      $singularVar = Inflector::variable($controller->modelClass);
	      if (!empty($controller->viewVars[$singularVar])) {
	        $primaryKeyValue = Hash::get($controller->viewVars[$singularVar], $path);
	      }
	    }

		return $primaryKeyValue;
	}

  protected function _displayFieldValue() {
    $controller = $this->_controller();
    $model = $this->_model();
    $displayFieldValue = null;
    $path = null;

    if (!empty($controller->modelClass) && !empty($model->displayField) && $model->displayField != $model->primaryKey) {
      $path = "{$controller->modelClass}.{$model->displayField}";
      if (!empty($controller->data)) {
        $displayFieldValue = Hash::get($controller->data, $path);
      }

      $singularVar = Inflector::variable($controller->modelClass);
      if (!empty($controller->viewVars[$singularVar])) {
        $displayFieldValue = Hash::get($controller->viewVars[$singularVar], $path);
      }
    }

    return $displayFieldValue;
  }

}
