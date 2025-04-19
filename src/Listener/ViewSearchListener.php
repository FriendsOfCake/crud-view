<?php
declare(strict_types=1);

namespace CrudView\Listener;

use Cake\Event\EventInterface;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Crud\Listener\BaseListener;
use function Cake\I18n\__d;

/**
 * @method \Cake\ORM\Table _model()
 */
class ViewSearchListener extends BaseListener
{
    /**
     * Default configuration
     *
     * ### Options
     *
     * - `enabled`: Indicates whether is listener is enabled.
     * - `autocomplete`: Whether to use auto complete for select fields. Default `true`.
     * - `select2`: Whether to use select2 for select fields. Default `true`.
     * - `collection`: The search behavior collection to use. Default "default".
     * - `fields`: Config for generating filter controls. If `null` the
     *   filter controls will be derived based on filter collection. You can use
     *   "form" key in filter config to specify control options. Default `null`.
     *
     * @var array
     */
    protected array $_defaultConfig = [
        'enabled' => null,
        'autocomplete' => true,
        'select2' => true,
        'collection' => 'default',
        'fields' => null,
    ];

    /**
     * Events this listerner is interested in.
     *
     * @return array<string, mixed>
     */
    public function implementedEvents(): array
    {
        return [
            'Crud.afterPaginate' => ['callable' => 'afterPaginate'],
        ];
    }

    /**
     * After paginate event callback.
     *
     * Only after a crud paginate call does this listener do anything. So listen
     * for that
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     */
    public function afterPaginate(EventInterface $event): void
    {
        if (!$this->_model()->behaviors()->has('Search')) {
            return;
        }

        $enabled = $this->getConfig('enabled') ?: !$this->_request()->is('api');
        if (!$enabled) {
            return;
        }

        $fields = $this->fields();

        $this->_controller()->viewBuilder()
            ->setVar('searchInputs', $fields)
            ->setHelpers(['Search.Search']);
    }

    /**
     * Get field options for search filter inputs.
     *
     * @return array
     */
    public function fields(): array
    {
        /** @var array $fields */
        $fields = $this->getConfig('fields', []);
        $config = $this->getConfig();

        $schema = $this->_model()->getSchema();
        $request = $this->_request();

        if ($fields) {
            $fields = Hash::normalize($fields, default: []);
        } else {
            $filters = $this->_model()->searchManager()->getFilters($config['collection']);

            foreach ($filters as $filter) {
                $opts = $filter->getConfig('form');
                if ($opts === false) {
                    continue;
                }

                $fields[$filter->name()] = $opts ?: [];
            }
        }

        foreach ($fields as $field => $opts) {
            $input = [
                'required' => false,
                'type' => 'text',
            ];

            if (str_ends_with($field, '_id') && $field !== '_id') {
                $input['type'] = 'select';
            }

            $input = $opts + $input;

            $input['value'] = $request->getQuery($field);

            if (!isset($input['options']) && $schema->getColumnType($field) === 'boolean') {
                $input['options'] = [1 => __d('crud', 'Yes'), 0 => __d('crud', 'No')];
                $input['type'] = 'select';
                if ($config['select2']) {
                    $input['class'] = 'no-select2';
                }
            }

            if (isset($input['options'])) {
                $input['empty'] ??= $this->getPlaceholder($field);

                if (empty($input['class']) && !$config['select2']) {
                    $input['class'] = 'no-select2';
                }

                $fields[$field] = $input;

                continue;
            }

            if ($input['type'] === 'select' && $config['autocomplete'] && empty($input['class'])) {
                $input['class'] = 'autocomplete';
            }

            if (
                !empty($input['class'])
                && strpos($input['class'], 'autocomplete') !== false
                && $input['type'] !== 'select'
            ) {
                $input['type'] = 'select';

                if (!empty($input['value'])) {
                    $input['options'][$input['value']] = $input['value'];
                }

                $input += [
                    'data-input-type' => 'text',
                    'data-tags' => 'true',
                    'data-allow-clear' => 'true',
                    'data-placeholder' => $this->getPlaceholder($field),
                ];
            }

            if ($input['type'] === 'text') {
                $input['placeholder'] ??= $this->getPlaceholder($field);
            }
            if ($input['type'] === 'select') {
                $input['empty'] ??= $this->getPlaceholder($field);
                if ($config['select2']) {
                    $input['data-placeholder'] ??= $this->getPlaceholder($field);
                }
            }

            if (
                !empty($input['class'])
                && strpos($input['class'], 'autocomplete') !== false
                && !isset($input['data-url'])
            ) {
                $urlArgs = [];

                $fieldKeys = $input['fields'] ?? ['id' => $field, 'value' => $field];
                if (is_array($fieldKeys)) {
                    foreach ($fieldKeys as $key => $val) {
                        $urlArgs[$key] = $val;
                    }
                }

                $input['data-url'] = Router::url(['action' => 'lookup', '_ext' => 'json', '?' => $urlArgs]);
            }

            unset($input['fields']);

            $fields[$field] = $input;
        }

        return $fields;
    }

    /**
     * Get placeholder text for a field.
     *
     * @param string $field Field name.
     * @return string
     */
    protected function getPlaceholder(string $field): string
    {
        if (str_contains($field, '.')) {
            [, $field] = explode('.', $field);
        }

        if (str_ends_with($field, '_id') && $field !== '_id') {
            $field = substr($field, 0, -3);
        }

        return Inflector::humanize($field);
    }
}
