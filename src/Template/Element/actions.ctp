<?php
foreach ($actions as $action => $config) {
    if ($this->request->action == $action) {
        continue;
    }

    $link = ['controller' => $config['controller'], 'action' => $action];
    if (!empty($singularVar)) {
        $link = ['controller' => $config['controller'], 'action' => $action, $singularVar->id];
    }

    if ($config['method'] !== 'GET') {
        echo $this->Form->postLink(
            $config['title'],
            $link,
            ['class' => 'btn btn-default', 'method' => $config['method']]
        );
        continue;
    }

    echo $this->Html->link(
        $config['title'],
        $link,
        ['class' => 'btn btn-default']
    );

}
