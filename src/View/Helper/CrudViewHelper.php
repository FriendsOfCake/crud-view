<?php
declare(strict_types=1);

namespace CrudView\View\Helper;

use Cake\Database\Schema\TableSchemaInterface;
use Cake\Datasource\EntityInterface;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use Cake\View\Helper;
use Cake\View\Helper\FormHelper;
use DateTime;
use DateTimeImmutable;

/**
 * @property \BootstrapUI\View\Helper\FormHelper $Form
 * @property \BootstrapUI\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\TimeHelper $Time
 */
class CrudViewHelper extends Helper
{
    /**
     * List of helpers used by this helper
     *
     * @var array
     */
    public $helpers = ['Form', 'Html', 'Time'];

    /**
     * Context
     *
     * @var \Cake\Datasource\EntityInterface
     */
    protected $_context;

    /**
     * Default config.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fieldFormatters' => null,
    ];

    /**
     * Set context
     *
     * @param \Cake\Datasource\EntityInterface $record Entity.
     * @return void
     */
    public function setContext(EntityInterface $record): void
    {
        $this->_context = $record;
    }

    /**
     * Get context
     *
     * @return \Cake\Datasource\EntityInterface
     */
    public function getContext(): EntityInterface
    {
        return $this->_context;
    }

    /**
     * Process a single field into an output
     *
     * @param string $field The field to process.
     * @param \Cake\Datasource\EntityInterface $data The entity data.
     * @param array $options Processing options.
     * @return string|null|array|bool|int
     */
    public function process(string $field, EntityInterface $data, array $options = [])
    {
        $this->setContext($data);

        $value = $this->fieldValue($data, $field);
        $options += ['formatter' => null];

        if ($options['formatter'] === 'element') {
            $context = $this->getContext();

            return $this->_View->element($options['element'], compact('context', 'field', 'value', 'options'));
        }

        if ($options['formatter'] === 'relation') {
            $relation = $this->relation($field);
            if ($relation) {
                return $relation['output'];
            }
        }

        if (is_callable($options['formatter'])) {
            return $options['formatter']($field, $value, $this->getContext(), $options, $this->getView());
        }

        $value = $this->introspect($field, $value, $options);

        return $value;
    }

    /**
     * Get the current field value
     *
     * @param \Cake\Datasource\EntityInterface $data The entity data.
     * @param string $field The field to extract, if null, the field from the entity context is used.
     * @return mixed
     */
    public function fieldValue(EntityInterface $data, string $field)
    {
        if (empty($data)) {
            $data = $this->getContext();
        }

        return $data->get($field);
    }

    /**
     * Returns a formatted output for a given field
     *
     * @param string $field Name of field.
     * @param mixed $value The value that the field should have within related data.
     * @param array $options Options array.
     * @return array|bool|null|int|string
     */
    public function introspect(string $field, $value, array $options = [])
    {
        $output = $this->relation($field);
        if ($output) {
            return $output['output'];
        }

        $type = $this->columnType($field);

        $fieldFormatters = $this->getConfig('fieldFormatters');
        if (isset($fieldFormatters[$type])) {
            if (is_callable($fieldFormatters[$type])) {
                return $fieldFormatters[$type]($field, $value, $this->getContext(), $options, $this->getView());
            }

            return $this->{$fieldFormatters[$type]}($field, $value, $options);
        }

        if ($type === 'boolean') {
            return $this->formatBoolean($field, $value, $options);
        }

        if (in_array($type, ['datetime', 'date', 'timestamp'])) {
            return $this->formatDate($field, $value, $options);
        }

        if ($type === 'time') {
            return $this->formatTime($field, $value, $options);
        }

        $value = $this->formatString($field, $value);

        if ($field === $this->getViewVar('displayField')) {
            return $this->formatDisplayField($value, $options);
        }

        return $value;
    }

    /**
     * Get column type from schema.
     *
     * @param string $field Field to get column type for
     * @return string
     */
    public function columnType(string $field): string
    {
        $schema = $this->schema();

        return $schema->getColumnType($field);
    }

    /**
     * Format a boolean value for display
     *
     * @param string $field Name of field.
     * @param mixed $value Value of field.
     * @param array $options Options array
     * @return string
     */
    public function formatBoolean(string $field, $value, array $options): string
    {
        return (bool)$value ?
            $this->Html->label(__d('crud', 'Yes'), ['type' => empty($options['inverted']) ? 'success' : 'danger']) :
            $this->Html->label(__d('crud', 'No'), ['type' => empty($options['inverted']) ? 'danger' : 'success']);
    }

    /**
     * Format a date for display
     *
     * @param string $field Name of field.
     * @param mixed $value Value of field.
     * @param array $options Options array.
     * @return string
     */
    public function formatDate(string $field, $value, array $options): string
    {
        if ($value === null) {
            return $this->Html->label(__d('crud', 'N/A'), ['type' => 'info']);
        }

        if (is_int($value) || is_string($value) || $value instanceof DateTime || $value instanceof DateTimeImmutable) {
            return $this->Time->timeAgoInWords($value, $options);
        }

        return $this->Html->label(__d('crud', 'N/A'), ['type' => 'info']);
    }

    /**
     * Format a time for display
     *
     * @param string $field Name of field.
     * @param mixed $value Value of field.
     * @param array $options Options array.
     * @return string
     */
    public function formatTime(string $field, $value, array $options): string
    {
        if ($value === null) {
            return $this->Html->label(__d('crud', 'N/A'), ['type' => 'info']);
        }
        $format = $options['format'] ?? null;

        if (is_int($value) || is_string($value) || $value instanceof DateTime || $value instanceof DateTimeImmutable) {
            return $this->Time->nice($value, $format);
        }

        return $this->Html->label(__d('crud', 'N/A'), ['type' => 'info']);
    }

    /**
     * Format a string for display
     *
     * @param string $field Name of field.
     * @param mixed $value Value of field.
     * @return string
     */
    public function formatString(string $field, $value): string
    {
        return h(Text::truncate((string)$value, 200));
    }

    /**
     * Format display field value.
     *
     * @param string $value Display field value.
     * @param array $options Options array.
     * @return string
     */
    public function formatDisplayField($value, array $options): string
    {
        return $this->createViewLink($value, ['escape' => false]);
    }

    /**
     * Returns a formatted relation output for a given field
     *
     * @param string $field Name of field.
     * @return mixed Array of data to output, false if no match found
     */
    public function relation(string $field)
    {
        $associations = $this->associations();
        if (empty($associations['manyToOne'])) {
            return false;
        }

        $data = $this->getContext();
        if (empty($data)) {
            return false;
        }

        foreach ($associations['manyToOne'] as $alias => $details) {
            if ($field !== $details['foreignKey']) {
                continue;
            }

            $entityName = $details['entity'];
            if (empty($data->$entityName)) {
                return false;
            }

            $entity = $data->$entityName;

            return [
                'alias' => $alias,
                'output' => $this->Html->link($entity->{$details['displayField']}, [
                    'plugin' => $details['plugin'],
                    'controller' => $details['controller'],
                    'action' => 'view',
                    $entity->{$details['primaryKey']},
                ]),
            ];
        }

        return false;
    }

    /**
     * Returns a hidden input for the redirect_url if it exists
     * in the request querystring, view variables, form data
     *
     * @return string|null
     */
    public function redirectUrl(): ?string
    {
        $redirectUrl = $this->getView()->getRequest()->getQuery('_redirect_url');
        $redirectUrlViewVar = $this->getViewVar('_redirect_url');

        if (!empty($redirectUrlViewVar)) {
            $redirectUrl = $redirectUrlViewVar;
        } else {
            $context = $this->Form->context();
            if ($context->val('_redirect_url')) {
                $redirectUrl = $context->val('_redirect_url');
            }
        }

        if (empty($redirectUrl)) {
            return null;
        }

        return $this->Form->hidden('_redirect_url', [
            'name' => '_redirect_url',
            'value' => $redirectUrl,
            'id' => null,
            'secure' => FormHelper::SECURE_SKIP,
        ]);
    }

    /**
     * Create relation link.
     *
     * @param string $alias Model alias.
     * @param array $relation Relation information.
     * @param array $options Options array to be passed to the link function
     * @return string
     */
    public function createRelationLink(string $alias, array $relation, array $options = []): string
    {
        return $this->Html->link(
            __d('crud', 'Add {0}', [Inflector::singularize(Inflector::humanize(Inflector::underscore($alias)))]),
            [
                'plugin' => $relation['plugin'],
                'controller' => $relation['controller'],
                'action' => 'add',
                '?' => [
                    $relation['foreignKey'] => $this->getViewVar('primaryKeyValue'),
                    '_redirect_url' => $this->getView()->getRequest()->getUri()->getPath(),
                ],
            ],
            $options
        );
    }

    /**
     * Create view link.
     *
     * @param string $title Link title
     * @param array $options Options array to be passed to the link function
     * @return string
     */
    public function createViewLink(string $title, array $options = []): string
    {
        return $this->Html->link(
            $title,
            ['action' => 'view', $this->getContext()->get($this->getViewVar('primaryKey'))],
            $options
        );
    }

    /**
     * Get current model class.
     *
     * @return string
     */
    public function currentModel(): string
    {
        return $this->getViewVar('modelClass');
    }

    /**
     * Get model schema.
     *
     * @return \Cake\Database\Schema\TableSchemaInterface
     */
    public function schema(): TableSchemaInterface
    {
        return $this->getViewVar('modelSchema');
    }

    /**
     * Get viewVar used for results.
     *
     * @return string
     */
    public function viewVar(): string
    {
        return $this->getViewVar('viewVar');
    }

    /**
     * Get associations.
     *
     * @return array List of associations.
     */
    public function associations(): array
    {
        return $this->getViewVar('associations') ?? [];
    }

    /**
     * Get a view variable.
     *
     * @param string $key View variable to get.
     * @return mixed
     */
    public function getViewVar(string $key)
    {
        return $this->_View->get($key);
    }

    /**
     * Get css classes
     *
     * @return string
     */
    public function getCssClasses(): string
    {
        $action = (string)$this->getView()->getRequest()->getParam('action');
        $pluralVar = $this->getViewVar('pluralVar');
        $viewClasses = (array)$this->getViewVar('viewClasses');
        $args = func_get_args();

        return implode(
            ' ',
            array_unique(array_merge(
                [
                    'scaffold-action',
                    sprintf('scaffold-action-%s', $action),
                    sprintf('scaffold-controller-%s', $pluralVar),
                    sprintf('scaffold-%s-%s', $pluralVar, $action),
                ],
                $args,
                $viewClasses
            ))
        );
    }
}
