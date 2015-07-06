<?php
foreach ($actions as $config) {
    $config += ['method' => 'GET'];

    if ((empty($config['url']['controller']) ||
        $this->request->controller === $config['url']['controller']) &&
        $this->request->action === $config['url']['action']
    ) {
        continue;
    }

    $url = $config['url'];
    if (!empty($singularVar)) {
        if (!empty($config['map'])) {
            foreach ($config['map'] as $key => $prop) {
                $url[$key] = $singularVar[$prop];
            }
        } else {
            $url[] = $singularVar->{$primaryKey};
        }
    }

    $options = ['class' => 'btn btn-default'];
    if (isset($config['options'])) {
        $options = $config['options'] + $options;
    }

    if ($config['method'] !== 'GET') {
        $options += [
            'confirm' => __d('crud', 'Are you sure you want to delete record #{0}?', [$singularVar->{$primaryKey}]),
            'method' => $config['method']
        ];
        echo $this->Form->postLink(
            $config['title'],
            $url,
            $options
        );
        continue;
    }

    echo $this->Html->link(
        $config['title'],
        $url,
        $options
    );

}
