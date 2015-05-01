<?php
use Cake\Utility\Inflector;

foreach ($actions['entity'] as $action) {

    if ($action === 'add') {
        continue;
    }

    if ($action === 'delete') {
        echo $this->Form->postLink(
            Inflector::humanize($action),
            ['action' => $action, $singularVar->id],
            ['class' => 'btn btn-default']
        );
        continue;
    }

    echo $this->Html->link(
        Inflector::humanize($action),
        ['action' => $action, $singularVar->id],
        ['class' => 'btn btn-default']
    );

}
