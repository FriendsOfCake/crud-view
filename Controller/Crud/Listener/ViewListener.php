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
		$this->_injectHelpers();

		$Controller = $this->_controller();
		$Controller->set('title', $this->_getPageTitle());
		$Controller->set('fields', $this->_getPageFields());
		$Controller->set('actions', $this->_getControllerActions());
		$Controller->set('associations', $this->_associations());
		$Controller->set($this->_getPageVariables());
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
			'primaryKey' => $this->_model()->primaryKey
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
		$request = $this->_request();
		$scaffoldTitle = $this->_action()->config('scaffoldPageTitle');

		if (!empty($scaffoldTitle)) {
			return $scaffoldTitle;
		}

		$actionName = Inflector::humanize(Inflector::underscore($request->action));
		$humanName = $this->_controllerName();

		$primaryKeyValue = $this->_primaryKeyValue();
		$displayFieldValue = $this->_displayFieldValue();

		if ($primaryKeyValue === null && $displayFieldValue === null) {
			$scaffoldTitle = sprintf('%s %s', $actionName, $humanName);
		} elseif ($displayFieldValue === null) {
			$scaffoldTitle = sprintf('%s %s #%s', $actionName, $humanName, $primaryKeyValue);
		} elseif ($primaryKeyValue === null) {
			$scaffoldTitle = sprintf('%s %s %s', $actionName, $humanName, $displayFieldValue);
		} else {
			$scaffoldTitle = sprintf('%s %s #%s: %s', $actionName, $humanName, $primaryKeyValue, $displayFieldValue);
		}

		return $scaffoldTitle;
	}

/**
 * Get fields that should be visible in the view
 *
 * @return array
 */
	protected function _getPageFields() {
		$fields = $this->_scaffoldFields();
		// $scaffoldFieldsData = $this->_scaffoldFieldExclude($model, $request, $scaffoldFieldsData, $_sort);
		// $fields = array_keys($fields);
		return $fields;
	}

/**
 * Returns fields to be displayed on scaffolded view
 *
 * @param boolean $sort Add sort keys to output
 * @return array List of fields
 */
	protected function _scaffoldFields($sort = true) {
		$model = $this->_model();
		$modelSchema = $model->schema();
		$blacklist = $this->_action()->config('scaffoldFieldExclude');

		$fields = array();
		$scaffoldFields = array_keys($modelSchema);
		foreach ($scaffoldFields as $scaffoldField) {
			$fields[$scaffoldField] = array();
		}

		$scaffoldFields = $fields;

		$_scaffoldFields = $this->_action()->config('scaffoldFields');
		if (!empty($_scaffoldFields)) {
			$fields = array();
			$_scaffoldFields = (array)$_scaffoldFields;
			foreach ($_scaffoldFields as $name => $options) {
				if (is_numeric($name) && !is_array($options)) {
					$name = $options;
					$options = array();
				}

				$fields[$name] = $options;
			}

			$scaffoldFields = array_intersect_key($scaffoldFields, $fields);
		}

		if (!empty($blacklist)) {
			$scaffoldFields = array_diff_key($scaffoldFields, array_combine($blacklist, $blacklist));
		}

		if ($sort) {
			$singularTable = Inflector::singularize($model->table);
			foreach ($scaffoldFields as $_field => $_options) {
				$entity = explode('.', $_field);
				$scaffoldFields[$_field]['__field__'] = $_field;
				$scaffoldFields[$_field]['__display_field__'] = false;
				$scaffoldFields[$_field]['__schema__'] = null;
				if (count($entity) == 1 || current($entity) == $model->alias) {
					$scaffoldFields[$_field]['__display_field__'] = in_array(end($entity), array($model->displayField, $singularTable));
					$scaffoldFields[$_field]['__schema__'] = $modelSchema[end($entity)]['type'];
				}
			}
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
		$_actions = $this->_crud()->config('actions');

		$model = array();
		$record = array();
		foreach ($_actions as $_actionName => $_config) {
			$_action = $this->_action($_actionName);
			$_type = $_action::ACTION_SCOPE;
			if ($_type === CrudAction::SCOPE_MODEL) {
				$model[] = $_actionName;
			} elseif ($_type === CrudAction::SCOPE_RECORD) {
				$record[] = $_actionName;
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
    $model = $this->_model();
    $primaryKeyValue = null;
    $path = null;

    if (!empty($controller->modelClass) && !empty($model->primaryKey)) {
      $path = "{$controller->modelClass}.{$model->primaryKey}";
      if (!empty($controller->data)) {
        $primaryKeyValue = Hash::get($controller->data, $path);
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
