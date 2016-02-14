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
     * @var array
     */
    protected $_defaultConfig = [
        'enabled' => null
    ];

    /**
     * implementedEvents
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
     * afterPaginate
     *
     * Only after a crud paginate call does this listener do anything. So listen
     * for that
     *
     * @param \Cake\Event\Event $event Event.
     * @return void
     */
    public function afterPaginate(Event $event)
    {
        $enabled = $this->config('enabled') ?: !$this->_request()->is('api');
        if (!$enabled) {
            return;
        }

        $fields = $this->fields();
        $this->_controller()->set('searchInputs', $fields);
    }

    /**
     * [fields description]
     *
     * @return array
     */
    public function fields()
    {
        return $this->config('fields') ?: $this->_deriveFields();
    }

    /**
     * [_deriveFields description]
     *
     * @return array
     */
    protected function _deriveFields()
    {
        $table = $this->_table();
        $request = $this->_request();

        if (!method_exists($table, 'searchConfiguration')) {
            return [];
        }

        $filters = $table->searchConfiguration();
        $schema = $table->schema();

        $fields = [];
        foreach ($filters->all() as $filter) {
            if ($filter->config('form') === false) {
                continue;
            }

            $searchParam = $filter->name();
            $field = $filter->field() ?: $searchParam;

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
                'label' => Inflector::humanize(preg_replace('/_id$/', '', $searchParam)),
                'required' => false,
                'type' => 'text'
            ];

            $value = $request->query($searchParam);
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
                $fields[$searchParam] = $input;
                continue;
            }

            if (empty($input['class'])) {
                $input['class'] = 'autocomplete';
            }

            if (empty($input['type'])) {
                $input['type'] = 'text';
            }

            $urlArgs = [];

            $fieldKeys = isset($input['fields']) ? $input['fields'] : ['id' => $field, 'value' => $field];
            foreach ($fieldKeys as $key => $val) {
                $urlArgs[$key] = $val;
            }

            unset($input['fields']);
            $url = ['action' => 'lookup', '?' => $urlArgs, '_ext' => 'json'];
            $input['data-url'] = Router::url($url);

            $fields[$searchParam] = $input;
        }
        return $fields;
    }
}
