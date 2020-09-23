<?php
$links = [];
foreach ($actions as $name => $config) {
    $config += ['method' => 'GET'];

    if (
        (empty($config['url']['controller']) || $this->request->getParam('controller') === $config['url']['controller']) &&
        (!empty($config['url']['action']) && $this->request->getParam('action') === $config['url']['action'])
    ) {
        continue;
    }

    $linkOptions = [];
    if (isset($config['options'])) {
        $linkOptions = $config['options'];
    }

    if ($config['method'] === 'DELETE') {
        $linkOptions += [
            'block' => 'action_link_forms',
            'confirm' => __d('crud', 'Are you sure you want to delete record #{0}?', [$singularVar->{$primaryKey}]),
        ];
    }

    if ($config['method'] !== 'GET') {
        $linkOptions += [
            'method' => $config['method'],
            'block' => 'action_link_forms',
        ];
    }

    if (!empty($config['callback'])) {
        $callback = $config['callback'];
        unset($config['callback']);
        $config['options'] = $linkOptions;
        $links[$name] = $callback($config, !empty($singularVar) ? $singularVar : null, $this);

        if ($links[$name]['method'] !== 'GET' && !isset($links[$name]['options']['block'])) {
            $links[$name]['options']['block'] = 'action_link_forms';
        }

        continue;
    }

    $url = $config['url'];
    if (!empty($singularVar)) {
        $setPrimaryKey = true;
        foreach ($url as $key => $value) {
            if (!is_string($value)) {
                continue;
            }

            if (strpos($value, ':primaryKey:') !== false) {
                $url[$key] = str_replace(
                    ':primaryKey:',
                    $singularVar->{$primaryKey},
                    $value
                );
                $setPrimaryKey = false;
            }
        }
        if ($setPrimaryKey) {
            $url[] = $singularVar->{$primaryKey};
        }
    }

    $links[$name] = [
        'title' => $config['title'],
        'url' => $url,
        'options' => $linkOptions,
        'method' => $config['method'],
    ];
}
?>

<?php
$btns = [];
// render primary actions at first
foreach ($actionGroups['primary'] as $action) {
    if (!isset($links[$action])) {
        continue;
    }

    $config = $links[$action];
    if (is_string($config)) {
        echo $config;
        continue;
    }

    if (empty($config['options']['class'])) {
        $config['options']['class'] = ['btn btn-secondary'];
    }

    $btns[] = $this->element('action-button', ['config' => $config]);
}
unset($actionGroups['primary']);

// render grouped actions
$groupedBtns = trim($this->element('action-groups', ['groups' => $actionGroups, 'links' => $links]));
if ($groupedBtns) {
    $btns[] = $groupedBtns;
}

echo implode('&nbsp;', $btns);
