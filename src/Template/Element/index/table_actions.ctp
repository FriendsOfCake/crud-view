<?php
use Cake\Utility\Inflector;

foreach ($actions['entity'] as $action) {

    if ($action === 'add') {
        continue;
    }

    echo $this->Html->link(
        Inflector::humanize($action),
        ['action' => $action, $singularVar->id],
        ['class' => 'btn btn-default']
    );

}
