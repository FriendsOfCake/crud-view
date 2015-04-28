<?php
use Cake\Utility\Inflector;

foreach ($actions['table'] as $action) {
    if ($this->request->action == $action) {
        continue;
    }

    echo $this->Html->link(
        Inflector::humanize($action),
        ['action' => $action],
        ['class' => 'btn btn-default']
    );
}
