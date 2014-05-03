<?php
namespace CrudView\Listener;

use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Crud\Listener\BaseListener;

class SearchListener extends BaseListener {

/**
 * Default configuration
 *
 * @var array
 */
	protected $_defaultConfig = [
		'enabled' => null
	];

/**
 * implementedEvents
 *
 * @return void
 */
	public function implementedEvents() {
		return [
			'Crud.afterPaginate' => array('callable' => 'afterPaginate')
		];
	}

/**
 * afterPaginate
 *
 * Only after a crud paginate call does this listener do anything. So listen
 * for that
 *
 * @param CakeEvent $e
 */
	public function afterPaginate(Event $event) {
		$enabled = $this->config('enabled') ?: !$this->_request()->is('api');
		if (!$enabled) {
			return;
		}

		$fields = $this->fields();
		$this->_controller()->set('searchInputs', $fields);
	}

	public function fields() {
		return $this->config('fields') ?: $this->_deriveFields();
	}

	protected function _deriveFields() {
		$table = $this->_table();
		$request = $this->_request();

		if (!method_exists($table, 'searchConfiguration')) {
			return [];
		}

		$filters = $table->searchConfiguration();
		$currentModel = $table->alias();
		$schema = $table->schema();

		$fields = [];
		foreach ($filters->all() as $filter) {
			if ($filter->config('form') === false) {
				continue;
			}

			$field = $filter->field();

			// Ignore multi-field filters for now
			if (is_array($field)) {
				continue;
			}

			$input = [];

			$filterFormConfig = $filter->config();
			if (!empty($filterFormConfig['form'])) {
				$input = $filterFormConfig['form'];
			}

			$input += [
				'label' => Inflector::humanize(preg_replace('/_id$/', '', $field)),
				'required' => false,
				'type' => 'text'
			];

			$value = $request->query($field);
			if ($value !== null) {
				$input['value'] = $value;
			}

			if (empty($input['options']) && $table->hasField($field)) {
				if ($schema->columnType($field) === 'boolean') {
					$input['options'] = ['No', 'Yes'];
					$input['type'] = 'select';
				}
			}

			if (!empty($input['options'])) {
				$input['empty'] = true;
				$fields[$field] = $input;
				continue;
			}

			$input['class'] = 'autocomplete';

			if (empty($input['type'])) {
				$input['type'] = 'text';
			}

			$urlArgs = [];

			$fieldKeys = isset($input['fields']) ? $input['fields'] : ['id' => $field, 'value' => $field];
			foreach ($fieldKeys as $key => $val) {
				$urlArgs[$key] = $val;
			}

			$url = ['action' => 'lookup', '?' => $urlArgs, 'ext' => 'json'];
			$input['data-url'] = Router::url($url);

			$fields[$field] = $input;
		}

		// debug($fields);
		return $fields;
	}

}
