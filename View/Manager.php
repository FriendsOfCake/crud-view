<?php
namespace CrudView\View;

class Manager implements \Iterator {

/**
 * List of Field instances
 *
 * @var array
 */
	protected $_fields = [];

/**
 * Get or create a new field
 *
 * @param  string $name
 * @param  array $config
 * @return Field
 */
	public function field($name, array $config = []) {
		if (!array_key_exists($name, $this->_fields)) {
			$this->_fields[$name] = new Field($name, $this, $config);
		}

		return $this->_fields[$name];
	}

/**
 * Mass configure fields
 *
 * If $fields is a \Cake\ORM\Table the columns will be extracted and used
 *
 * Else an associative array can be used, where the key is the field name
 * and the value is an array with configuration options
 *
 * @param  mixed $fields
 * @return \CrudView\View\Manager
 */
	public function all($fields) {
		if ($fields instanceof \Cake\ORM\Table) {
			$fields = array_flip($fields->schema()->columns());
		}

		if (!is_array($fields)) {
			throw new \Exception('Fields must be an array');
		}

		foreach ($fields as $field => $config) {
			$this->field($field, (array)$config);
		}

		return $this;
	}

/**
 * Get input configuration for all fields
 *
 * @return array
 */
	public function inputs() {
		$form = [];

		foreach ($this->_fields as $field) {
			$form[$field->name()] = $field->input();
		}

		return $form;
	}

/**
 * Get blacklisted fields
 *
 * @return array
 */
	public function blacklist() {
		$blacklist = [];

		foreach ($this->_fields as $field) {
			if ($field->isBlacklisted()) {
				$blacklist[] = $field->name();
			}
		}

		return $blacklist;
	}

/**
 * Get pagination fields
 *
 * @return array
 */
	public function paginate() {
		$fields = [];

		foreach ($this->_fields as $field) {
			if ($field->isBlacklisted()) {
				continue;
			}

			$fields[] = $field;
		}

		return $fields;
	}

	public function current() {
		return current($this->_fields);
	}

	public function key() {
		return key($this->_fields);
	}

	public function next() {
		return next($this->_fields);
	}

	public function rewind() {
		return reset($this->_fields);
	}

	public function valid() {
		return key($this->_fields) !== null;
	}

}
