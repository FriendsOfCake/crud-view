<?php
foreach ($actions as $config) {
    $config += ['method' => 'GET'];

    if ((empty($config['url']['controller']) || $this->request->controller === $config['url']['controller']) &&
        (!empty($config['url']['action']) && $this->request->action === $config['url']['action'])
    ) {
        continue;
    }

    $linkOptions = ['class' => 'btn btn-default'];
    if (isset($config['options'])) {
        $linkOptions = $config['options'] + $linkOptions;
    }

    if ($config['method'] === 'DELETE') {
        $linkOptions += [
            'confirm' => __d('crud', 'Are you sure you want to delete record #{0}?', [$singularVar->{$primaryKey}])
        ];
    }

    if ($config['method'] !== 'GET') {
        $linkOptions += [
            'method' => $config['method']
        ];
    }

    if (!empty($config['callback'])) {
        $callback = $config['callback'];
        unset($config['callback']);
        $config['linkOptions'] = $linkOptions;
        echo $callback($config, !empty($singularVar) ? $singularVar : null);
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

    if ($config['method'] !== 'GET') {
        echo $this->Form->postLink(
            $config['title'],
            $url,
            $linkOptions
        );
        continue;
    }

    echo $this->Html->link(
        $config['title'],
        $url,
        $linkOptions
    );
}
