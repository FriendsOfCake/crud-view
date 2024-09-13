<?php
declare(strict_types=1);

namespace CrudView\View\Helper;

use BackedEnum;
use Cake\Chronos\ChronosDate;
use Cake\Chronos\ChronosTime;
use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\Database\Type\EnumLabelInterface;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\SchemaInterface;
use Cake\I18n\Date;
use Cake\I18n\Time;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
use Cake\View\Form\EntityContext;
use Cake\View\Helper;
use UnitEnum;
use function Cake\Core\h;
use function Cake\I18n\__d;

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
    protected array $helpers = ['Form', 'Html', 'Time'];

    /**
     * Entity context
     *
     * @var \Cake\View\Form\EntityContext
     */
    protected EntityContext $_context;

    /**
     * Default config.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'fieldFormatters' => null,
        'dateTimeFormat' => null,
        'dateFormat' => null,
        'timeFormat' => null,
    ];

    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        $this->setConfig(Configure::read('CrudView.helperConfig', []));
    }

    /**
     * Set context
     *
     * @param \Cake\Datasource\EntityInterface $record Entity.
     * @return void
     */
    public function setContext(EntityInterface $record): void
    {
        $this->_context = new EntityContext(['entity' => $record]);
    }

    /**
     * Get context
     *
     * @return \Cake\View\Form\EntityContext
     */
    public function getContext(): EntityContext
    {
        return $this->_context;
    }

    /**
     * Process a single field into an output
     *
     * @param string $field The field to process.
     * @param \Cake\Datasource\EntityInterface $data The entity data.
     * @param array $options Processing options.
     * @return array|string|int|bool|null
     */
    public function process(string $field, EntityInterface $data, array $options = []): string|array|bool|int|null
    {
        $this->setContext($data);

        $value = $this->getContext()->val($field, ['schemaDefault' => false]);
        $options += ['formatter' => null];

        if ($options['formatter'] === 'element') {
            $context = $this->getContext()->entity();

            return $this->_View->element($options['element'], compact('context', 'field', 'value', 'options'));
        }

        if ($options['formatter'] === 'relation') {
            $relation = $this->relation($field);
            if ($relation) {
                return $relation['output'];
            }
        }

        if (is_callable($options['formatter'])) {
            return $options['formatter']($field, $value, $this->getContext()->entity(), $options, $this->getView());
        }

        $value = $this->introspect($field, $value, $options);

        return $value;
    }

    /**
     * Get the current field value
     *
     * @param string $field The field to extract, if null, the field from the entity context is used.
     * @param \Cake\Datasource\EntityInterface|null $data The entity data.
     * @return mixed
     */
    public function fieldValue(string $field, ?EntityInterface $data = null): mixed
    {
        if ($data === null) {
            return $this->getContext()->val($field, ['schemaDefault' => false]);
        }

        return $data->get($field);
    }

    /**
     * Returns a formatted output for a given field
     *
     * @param string $field Name of field.
     * @param mixed $value The value that the field should have within related data.
     * @param array $options Options array.
     * @return array|string|int|bool|null
     */
    public function introspect(string $field, mixed $value, array $options = []): array|bool|int|string|null
    {
        $output = $this->relation($field);
        if ($output) {
            return $output['output'];
        }

        $type = $this->columnType($field);

        $fieldFormatters = $this->getConfig('fieldFormatters');
        if (isset($fieldFormatters[$type])) {
            /** @psalm-suppress PossiblyNullArrayOffset */
            if (is_callable($fieldFormatters[$type])) {
                return $fieldFormatters[$type](
                    $field,
                    $value,
                    $this->getContext()->entity(),
                    $options,
                    $this->getView()
                );
            }

            /** @psalm-suppress PossiblyNullArrayOffset */
            return $this->{$fieldFormatters[$type]}($field, $value, $options);
        }

        if ($type === 'boolean') {
            return $this->formatBoolean($field, $value, $options);
        }

        if (in_array($type, ['datetime', 'date', 'time', 'timestamp'], true)) {
            return $this->formatDateTime($field, $value, $options);
        }

        if ($type !== null && str_starts_with($type, 'enum-')) {
            return $this->formatEnum($field, $value, $options);
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
     * @return string|null
     */
    public function columnType(string $field): ?string
    {
        return $this->getContext()->type($field);
    }

    /**
     * Format a boolean value for display
     *
     * @param string $field Name of field.
     * @param mixed $value Value of field.
     * @param array $options Options array
     * @return string
     */
    public function formatBoolean(string $field, mixed $value, array $options): string
    {
        return (bool)$value ?
            $this->Html->badge(__d('crud', 'Yes'), ['class' => empty($options['inverted']) ? 'success' : 'danger']) :
            $this->Html->badge(__d('crud', 'No'), ['class' => empty($options['inverted']) ? 'danger' : 'success']);
    }

    /**
     * Format a date for display
     *
     * @param string $field Name of field.
     * @param mixed $value Value of field.
     * @param array $options Options array.
     * @return string
     */
    public function formatDateTime(string $field, mixed $value, array $options): string
    {
        if ($value === null) {
            return $this->Html->badge(__d('crud', 'N/A'), ['class' => 'info']);
        }

        if ($value instanceof Date) {
            return (string)$value->i18nFormat($options['format'] ?? $this->getConfig('dateFormat'));
        }

        if ($value instanceof Time) {
            return (string)$value->i18nFormat($options['format'] ?? $this->getConfig('timeFormat'));
        }

        if ($value instanceof ChronosDate || $value instanceof ChronosTime) {
            return (string)$value;
        }

        return (string)$this->Time->i18nFormat($value, $options['format'] ?? $this->getConfig('dateTimeFormat'), '')
            ?: $this->Html->badge(__d('crud', 'N/A'), ['class' => 'info']);
    }

    /**
     * Format an enum for display
     *
     * @param string $field Name of field.
     * @param \UnitEnum|\BackedEnum|string|int|null $value Value of field.
     * @return string
     */
    public function formatEnum(string $field, UnitEnum|BackedEnum|string|int|null $value, array $options): string
    {
        if ($value === null) {
            return $this->Html->badge(__d('crud', 'N/A'), ['class' => 'info']);
        }

        if (is_scalar($value)) {
            return (string)$value;
        }

        return $value instanceof EnumLabelInterface ?
            $value->label() : Inflector::humanize(Inflector::underscore($value->name));
    }

    /**
     * Format a string for display
     *
     * @param string $field Name of field.
     * @param mixed $value Value of field.
     * @return string
     */
    public function formatString(string $field, mixed $value): string
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
    public function formatDisplayField(string $value, array $options): string
    {
        return $this->createViewLink($value, ['escape' => false]);
    }

    /**
     * Returns a formatted relation output for a given field
     *
     * @param string $field Name of field.
     * @return mixed Array of data to output, false if no match found
     */
    public function relation(string $field): mixed
    {
        $associations = $this->associations();
        if (empty($associations['manyToOne'])) {
            return false;
        }

        $data = $this->getContext()->entity();

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
                'output' => $this->Html->link((string)$entity->{$details['displayField']}, [
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

        try {
            $this->Form->unlockField('_redirect_url');
        } catch (CakeException) {
            // If FormProtectorComponent is not loaded, FormHelper::unlockField() throws an exception
        }

        return $this->Form->hidden('_redirect_url', [
            'name' => '_redirect_url',
            'value' => $redirectUrl,
            'id' => null,
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
        $entity = $this->getContext()->entity();
        assert($entity instanceof EntityInterface);

        return $this->Html->link(
            $title,
            ['action' => 'view', $entity->get($this->getViewVar('primaryKey'))],
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
     * @return \Cake\Datasource\SchemaInterface
     */
    public function schema(): SchemaInterface
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
    public function getViewVar(string $key): mixed
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
