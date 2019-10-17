<?php
declare(strict_types=1);

namespace CrudView\Listener;

use Cake\Event\EventInterface;
use Cake\Routing\Router;
use Crud\Listener\BaseListener;

class ViewSearchListener extends BaseListener
{
    /**
     * Default configuration
     *
     * ### Options
     *
     * - `enabled`: Indicates whether is listener is enabled.
     * - `autocomplete`: Whether to use auto complete for select fields. Default `true`.
     * - `selectize`: Whether to use selectize for select fields. Default `true`.
     * - `collection`: The search behavior collection to use. Default "default".
     * - `fields`: Config for generating filter controls. If `null` the
     *   filter controls will be derived based on filter collection. You can use
     *   "form" key in filter config to specify control options. Default `null`.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'enabled' => null,
        'autocomplete' => true,
        'selectize' => true,
        'collection' => 'default',
        'fields' => null,
    ];

    /**
     * Events this listerner is interested in.
     *
     * @return array
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
    public function afterPaginate(EventInterface $event)
    {
        $event;
        if (!$this->_table()->behaviors()->has('Search')) {
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
    public function fields()
    {
        return $this->getConfig('fields') ?: $this->_deriveFields();
    }

    /**
     * Derive field options for search filter inputs based on filter collection.
     *
     * @return array
     */
    protected function _deriveFields()
    {
        $config = $this->getConfig();
        $table = $this->_table();

        if (method_exists($table, 'searchConfiguration')) {
            $searchManager = $table->searchConfiguration();
        } else {
            $searchManager = $table->searchManager();
        }

        $fields = [];
        $schema = $table->getSchema();
        $request = $this->_request();

        foreach ($searchManager->getFilters($config['collection']) as $filter) {
            if ($filter->getConfig('form') === false) {
                continue;
            }

            $field = $filter->name();
            $input = [
                'required' => false,
                'type' => 'text',
            ];

            if (substr($field, -3) === '_id' && $field !== '_id') {
                $input['type'] = 'select';
            }

            $filterFormConfig = $filter->getConfig('form');
            if (!empty($filterFormConfig)) {
                $input = $filterFormConfig + $input;
            }

            $input['value'] = $request->getQuery($field);

            if (empty($input['options']) && $schema->getColumnType($field) === 'boolean') {
                $input['options'] = ['No', 'Yes'];
                $input['type'] = 'select';
            }

            if (!empty($input['options'])) {
                $input['empty'] = true;
                if (empty($input['class']) && !$config['selectize']) {
                    $input['class'] = 'no-selectize';
                }

                $fields[$field] = $input;

                continue;
            }

            if (empty($input['class']) && $config['autocomplete']) {
                $input['class'] = 'autocomplete';
            }

            $urlArgs = [];

            $fieldKeys = $input['fields'] ?? ['id' => $field, 'value' => $field];
            if (is_array($fieldKeys)) {
                foreach ($fieldKeys as $key => $val) {
                    $urlArgs[$key] = $val;
                }
            }

            unset($input['fields']);
            $url = array_merge(['action' => 'lookup', '_ext' => 'json'], $urlArgs);
            $input['data-url'] = Router::url($url);

            $fields[$field] = $input;
        }

        return $fields;
    }
}
