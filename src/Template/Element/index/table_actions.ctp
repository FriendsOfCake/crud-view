<?php
foreach ($actions['entity'] as $action => $config) {
    if ($this->request->action == $action) {
        continue;
    }

    if ($config['method'] !== 'GET') {
        echo $this->Form->postLink(
            $config['title'],
            ['controller' => $config['controller'], 'action' => $action, $singularVar->id],
            ['class' => 'btn btn-default', 'method' => $config['method']]
        );
        continue;
    }

    echo $this->Html->link(
        $config['title'],
        ['controller' => $config['controller'], 'action' => $action, $singularVar->id],
        ['class' => 'btn btn-default']
    );

}
