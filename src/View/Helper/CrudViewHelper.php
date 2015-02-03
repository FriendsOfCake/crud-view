<?php
namespace CrudView\View\Helper;

use Cake\ORM\Entity;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Utility\String;
use Cake\View\Helper;

class CrudViewHelper extends Helper
{

/**
 * List of helpers used by this helper
 *
 * @var array
 */
    public $helpers = ['Form', 'Html', 'Time', 'Paginator'];

    protected $_context = array();

    public function setContext(Entity $record)
    {
        $this->_context = $record;
    }

    public function getContext()
    {
        return $this->_context;
    }

/**
 * Process a single field into an output
 *
 * @param  string $field    The field to process
 * @param  array $data      The raw entity data
 * @param  array $options Processing options
 * @return string
 */
    public function process($field, Entity $data, array $options = [])
    {
        $this->setContext($data);

        $value = $this->fieldValue($data, $field);

        $options = (array)$options;
        $options += ['formatter' => null];

        switch ($options['formatter']) {
            case 'element':
                return $this->_View->element($options['element'], compact('field', 'value', 'options'));

            case 'relation':
                return $this->relation($field, $value, $options);

            default:
                return $this->introspect($field, $value, $options);
        }
    }

/**
 * Get the current field value
 *
 * @param  array    $data  The raw entity data array
 * @param  string $field The field to extract,
 *                       if null, the field from the entity context is used
 * @return mixed
 */
    public function fieldValue(Entity $data = null, $field = null)
    {
        if (empty($field)) {
            $field = $this->field();
        }

        if (empty($data)) {
            $data = $this->getContext();
        }

        return $data->get($field);
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
    public function introspect($field, $value, array $options = array())
    {
        $output = $this->relation($field, $value, $options);

        if ($output) {
            return $output['output'];
        }

        $type = $this->schema()->columnType($field);

        if ($type === 'boolean') {
            return $this->formatBoolean($field, $value, $options);
        }

        if (in_array($type, array('datetime', 'date', 'timestamp'))) {
            return $this->formatDate($field, $value, $options);
        }

        if ($type == 'time') {
            return $this->formatTime($field, $value, $options);
        }

        return $this->formatString($field, $value, $options);
    }

/**
 * Format a boolean value for display
 *
 * @param  [type] $field   [description]
 * @param  [type] $value   [description]
 * @param  array  $options [description]
 * @return [type]          [description]
 */
    public function formatBoolean($field, $value, array $options)
    {
        return !!$value ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>';
    }

/**
 * Format a date for display
 *
 * @param  [type] $field   [description]
 * @param  [type] $value   [description]
 * @param  array  $options [description]
 * @return [type]          [description]
 */
    public function formatDate($field, $value, array $options)
    {
        return $this->Time->timeAgoInWords($value, $options);
    }

/**
 * Format a time for display
 *
 * @param  [type] $field   [description]
 * @param  [type] $value   [description]
 * @param  array  $options [description]
 * @return [type]          [description]
 */
    public function formatTime($field, $value, array $options)
    {
        return $this->Time->nice($value, $options);
    }

/**
 * Format a string for display
 *
 * @param  [type] $field   [description]
 * @param  [type] $value   [description]
 * @param  array  $options [description]
 * @return [type]          [description]
 */
    public function formatString($field, $value, array $options)
    {
        return h(String::truncate($value, 200));
    }

/**
 * Returns a formatted relation output for a given field
 *
 * @param string    $field
 * @param array     $value
 * @param array     $options
 * @var mixed array of data to output, false if no match found
 */
    public function relation($field, $value, array $options = [])
    {
        $associations = $this->associations();
        if (empty($associations['belongsTo'])) {
            return false;
        }

        $data = $this->getContext();
        foreach ($associations['belongsTo'] as $alias => $details) {
            if ($field !== $details['foreignKey']) {
                continue;
            }

            return [
                'alias' => $alias,
                'output' => $this->Html->link($data[$alias][$details['displayField']], [
                    'controller' => $details['controller'],
                    'action' => 'view',
                    $data[$alias][$details['primaryKey']]
                ])
            ];
        }

        return false;
    }

/**
 * Returns a hidden input for the redirect_url if it exists
 * in the request querystring, view variables, form data
 *
 * @var array
 */
    public function redirectUrl()
    {
        $redirectUrl = $this->request->query('redirect_url');
        $redirectUrlViewVar = $this->getViewVar('redirect_url');

        if (!empty($redirectUrlViewVar)) {
            $redirectUrl = $redirectUrlViewVar;
        } else {
            $redirectUrl = $this->Form->value('redirect_url');
        }

        if (empty($redirectUrl)) {
            return null;
        }

        return $this->Form->hidden('redirect_url', array(
            'name' => 'redirect_url',
            'value' => $redirectUrl,
            'id' => null,
            'secure' => FormHelper::SECURE_SKIP
        ));
    }

    public function createRelationLink($alias, $relation)
    {
        return $this->Html->link(
            __d('crud', "Add %s", Inflector::humanize(Inflector::underscore($alias))),
            [
                'plugin' => $relation['plugin'],
                'controller' => $relation['controller'],
                'action' => 'add',
                '?' => [
                    $relation['foreignKey'] => $this->getViewVar('primaryKeyValue'),
                    'redirect_url' => $this->request->here
                ]
            ]
        );
    }

    public function currentModel()
    {
        return $this->getViewVar('modelClass');
    }

    public function schema()
    {
        return $this->getViewVar('modelSchema');
    }

    public function viewVar()
    {
        return $this->getViewVar('viewVar');
    }

    public function associations()
    {
        return $this->getViewVar('associations');
    }

    public function getViewVar($key = null)
    {
        return Hash::get($this->_View->viewVars, $key);
    }
}
