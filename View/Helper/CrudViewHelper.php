<?php
App::uses('AppHelper', 'View/Helper');

class CrudViewHelper extends AppHelper {

/**
 * List of helpers used by this helper
 *
 * @var array
 */
	public $helpers = array('Form', 'Html', 'Time', 'Paginator');

	protected $_context = array();

	public function setContext(array $record) {
		$this->_context = $record;
	}

	public function getContext() {
		return $this->_context;
	}

	public function sort($field, $options = array()) {
		return $this->Paginator->sort($field, null, $options);
	}

/**
 * Process a single field into an output
 *
 * @param  string $field 	The field to process
 * @param  array $data 		The raw entity data
 * @param  array $options Processing options
 * @return string
 */
	public function process($field, array $data, array $options) {
		$this->setContext($data);
		$this->setEntity(sprintf('%s.%s', $this->currentModel(), $field), true);

		$value = $this->fieldValue();

		switch ($options['formatter']) {
			case 'element':
				return $this->_View->element($options['element'], compact('field', 'value', 'options'));

			case 'relation':
				return $this->relation($field, $value, $options);

			default:
				return $this->format($field, $value, $options);
		}

	}

/**
 * Get the current field value
 *
 * @param  array 	$data  The raw entity data array
 * @param  string $field The field to extract,
 *                       if null, the field from the entity context is used
 * @return mixed
 */
	public function fieldValue(array $data = null, $field = null) {
		if (empty($field)) {
			$field = $this->field();
		}

		if (empty($data)) {
			$data = $this->getContext();
		}

		return Hash::get($data, sprintf('%s.%s', $this->model(), $field));
	}

/**
 * Returns a formatted output for a given field
 *
 * @param string $field name of the field
 * @param mixed $value the value that the field should have within related data
 * @param array $data an array of data related to this field
 * @param array $schema a Model schema
 * @param array $associations an array of associations to be used
 * @var string formatted value
 */
	public function format($field, $value, array $options = array()) {
		$output = $this->relation($field, $value, $options);

		if ($output) {
			return $output['output'];
		}

		$schema = $this->schema();
		$type = Hash::get($schema, "{$field}.type");

		if ($type === 'boolean') {
			return !!$value ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>';
		}

		if (in_array($type, array('datetime', 'date', 'timestamp'))) {
			return $this->Time->timeAgoInWords($value, $options);
		}

		if ($type == 'time') {
			return $this->Time->nice($value);
		}

		return h(String::truncate($value, 200));
	}

/**
 * Returns a formatted relation output for a given field
 *
 * @param string 	$field
 * @param array 	$value
 * @param array 	$options
 * @var mixed array of data to output, false if no match found
 */
	public function relation($field, $value, array $options) {
		$associations = $this->associations();
		if (empty($associations['belongsTo'])) {
			return false;
		}

		$data = $this->getContext();
		foreach ($associations['belongsTo'] as $alias => $details) {
			if ($field !== $details['foreignKey']) {
				continue;
			}

			return array(
				'alias' => $alias,
				'output' => $this->Html->link($data[$alias][$details['displayField']], array(
					'controller' => $details['controller'],
					'action' => 'view',
					$data[$alias][$details['primaryKey']]
				))
			);
		}

		return false;
	}

/**
 * Returns a hidden input for the redirect_url if it exists
 * in the request querystring, view variables, form data
 *
 * @var array
 */
	public function redirectUrl() {
		$redirectUrl = $this->_View->request->query('redirect_url');
		if (!empty($this->_View->viewVars['redirect_url'])) {
			$redirectUrl = $this->_View->viewVars['redirect_url'];
		} else {
			$redirectUrl = $this->Form->value('redirect_url');
		}

		if (!empty($redirectUrl)) {
			return $this->Form->hidden('redirect_url', array(
				'name' => 'redirect_url',
				'value' => $redirectUrl,
				'id' => null,
				'secure' => FormHelper::SECURE_SKIP
			));
		}

		return null;
	}

	public function currentModel() {
		return $this->getViewVar('modelClass');
	}

	public function schema() {
		return $this->getViewVar('modelSchema');
	}

	public function viewVar() {
		return $this->getViewVar('viewVar');
	}

	public function associations() {
		return $this->getViewVar('associations');
	}

	public function getViewVar($key = null) {
		return Hash::get($this->_View->viewVars, $key);
	}

}
