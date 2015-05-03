<?php
foreach ($actions['entity'] as $action => $config) {
    if ($this->request->action == $action) {
        continue;
    }

    if ($action === 'delete') {
        echo $this->Form->postLink(
            $config['title'],
            ['controller' => $config['controller'], 'action' => $action, $singularVar->id],
            ['class' => 'btn btn-default']
        );
        continue;
    }

    echo $this->Html->link(
        $config['title'],
        ['controller' => $config['controller'],'action' => $action, $singularVar->id],
        ['class' => 'btn btn-default']
    );

}
