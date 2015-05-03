<?php
foreach ($actions['table'] as $action => $config) {
    if ($this->request->action == $action) {
        continue;
    }

    echo $this->Html->link(
        $config['title'],
        ['controller' => $config['controller'],'action' => $action],
        ['class' => 'btn btn-default']
    );
}
