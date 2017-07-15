<?php
namespace CrudView\Listener;

use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use Crud\Listener\BaseListener;

class ViewSearchListener extends BaseListener
{

    /**
     * Default configuration
     *
     * ### Options
     *
     * - `enable`: Indicates whether is listener is enabled.
     * - `autocomplete`: Whether to use auto complete for select fields. Default `true`.
     * - `selectize`: Whether to use selectize for select fields. Default `true`.
     * - `collection`: The search behavior collection to use. Default "default".
     * - `fields`: Fields config for generation filter inputs. If `null` the
     *   field inputs will be derived based on filter collection. Default `null``.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'enabled' => null,
        'autocomplete' => true,
        'selectize' => true,
        'collection' => 'default',
        'fields' => null
    ];

    /**
     * Events this listerner is interested in.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'Crud.afterPaginate' => ['callable' => 'afterPaginate']
        ];
    }

    /**
     * After paginate event callback.
     *
     * Only after a crud paginate call does this listener do anything. So listen
     * for that
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function afterPaginate(Event $event)
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
        $this->_controller()->set('searchInputs', $fields);
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
        $table = $this->_table();
        $request = $this->_request();

        if (method_exists($table, 'searchConfiguration')) {
            $filters = $table->searchConfiguration();
        } else {
            $filters = $table->searchManager()->useCollection($config['collection']);
        }

        $fields = [];
        $schema = $table->getSchema();
        $config = $this->getConfig();

        foreach ($filters->all() as $filter) {
            if ($filter->getConfig('form') === false) {
                continue;
            }

            $field = $filter->name();
            $input = [];

            $filterFormConfig = $filter->getConfig();
            if (!empty($filterFormConfig['form'])) {
                $input = $filterFormConfig['form'];
            }

            $input += [
                'label' => Inflector::humanize(preg_replace('/_id$/', '', $field)),
                'required' => false,
                'type' => 'text'
            ];

            if (substr($field, -3) === '_id' && $field !== '_id') {
                $input['type'] = 'select';
            }

            $input['value'] = $request->getQuery($field);

            if (empty($input['options']) && $schema->columnType($field) === 'boolean') {
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

            $fieldKeys = isset($input['fields']) ? $input['fields'] : ['id' => $field, 'value' => $field];
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
