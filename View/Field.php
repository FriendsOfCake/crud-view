<?php
namespace CrudView\View;

use Cake\Core\InstanceConfigTrait;

class Field {

	use InstanceConfigTrait;

/**
 * Default field configuration
 *
 * @var array
 */
	protected $_defaultConfig = [
		'blacklist' => false,
		'paginate' => [],
		'input' => [],
		'value' => []
	];

/**
 * Reference to the Field Manager
 *
 * @var \CrudView\View\Manager
 */
	protected $_manager;

/**
 * Constructor
 *
 * @param string  $name
 * @param Manager $manager
 * @param array   $config
 */
	public function __construct($name, Manager $manager, array $config = []) {
		$config += [
			'name' => $name
		];

		$this->config($config);
		$this->_manager = $manager;
	}

/**
 * Paginate options
 *
 * @param array $value
 * @return mixed
 */
	public function paginate(array $value = null) {
		return $this->_fluent('paginate', $value);
	}

/**
 * Input options for the field in forms
 *
 * @param  array $value
 * @return mixed
 */
	public function input(array $value = null) {
		return $this->_fluent('input', $value);
	}

/**
 * Options for displaying in normal HTML settings
 *
 * @param  array $value
 * @return mixed
 */
	public function value(array $value = null) {
		return $this->_fluent('value', $value);
	}

/**
 * Get or set the field name.
 *
 * The name must match whats in your entity column name
 *
 * @param  string $value]
 * @return mixed
 */
	public function name($value = null) {
		return $this->_fluent('name', $value);
	}

/**
 * Get or set the field alias.
 *
 * The alias is used in paginate headers, form labels and more
 *
 * @param  string $value
 * @return mixed
 */
	public function alias($value = null) {
		return $this->_fluent('alias', $value);
	}

/**
 * Blacklist a field for showing up in the template output
 *
 * @return \CrudView\View\Field
 */
	public function blacklist($value = true) {
		return $this->_fluent('blacklist', $value);
	}

/**
 * Is the field blacklisted or not
 *
 * @return boolean
 */
	public function isBlacklisted() {
		return !!$this->config('blacklist');
	}

/**
 * Magic __toString method
 *
 * Will always return the field name
 *
 * @return string
 */
	public function __toString() {
		return $this->config('name');
	}

/**
 * Finish configuration of the field and return
 * to the FieldManager.
 *
 * Useful if you obsess about fluent interfaces
 *
 * @return \CrudView\View\Manager
 */
	public function end() {
		return $this->_manager;
	}

/**
 * Helper method for fluent interfaces
 *
 * If it's a 'set' operation, set the value and return the current object
 * for easy fluent coding
 *
 * If it's a 'get' operation, return the actual field value
 *
 * @param  string $key
 * @param  null|mixed $value
 * @return mixed
 */
	protected function _fluent($key, $value = null) {
		if ($value === null) {
			return $this->config($key);
		}

		$this->config($key, $value);
		return $this;
	}

}
