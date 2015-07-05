<?php
namespace CrudView\View\Cell;

use Cake\Datasource\ConnectionManager;
use Cake\View\Cell;
use Cake\Utility\Inflector;

class TablesListCell extends Cell
{

    public function display($tables, $blacklist)
    {
        if (empty($tables)) {
            $connection = ConnectionManager::get('default');
            $schema = $connection->schemaCollection();
            $tables = $schema->listTables();
            ksort($tables);

            if (!empty($blacklist)) {
                $tables = array_diff($tables, $blacklist);
            }
        }

        $normal = [];
        foreach ($tables as $table => $config) {
            if (is_string($config)) {
                $config = ['table' => $config];
            }

            if (is_int($table)) {
                $table = $config['table'];
            }

            $config += [
                'action' => 'index',
                'title' => Inflector::humanize($table),
                'controller' => Inflector::camelize($table)
            ];

            $normal[$table] = $config;
        }

        return $this->set('tables', $normal);
    }

}
