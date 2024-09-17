<?php
declare(strict_types=1);

namespace CrudView\View\Cell;

use Cake\Datasource\ConnectionManager;
use Cake\Utility\Inflector;
use Cake\View\Cell;

class TablesListCell extends Cell
{
    /**
     * Default cell method.
     *
     * @param array $tables Tables list.
     * @param array $blacklist Blacklisted tables list.
     * @return void
     */
    public function display(?array $tables = null, ?array $blacklist = null): void
    {
        if ($tables === null) {
            /** @var \Cake\Database\Connection $connection */
            $connection = ConnectionManager::get('default');
            $schema = $connection->getSchemaCollection();
            $tables = $schema->listTables();
            sort($tables);

            /** @psalm-suppress RiskyTruthyFalsyComparison */
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
                'controller' => Inflector::camelize($table),
            ];

            $normal[$table] = $config;
        }

        $this->set('tables', $normal);
    }
}
