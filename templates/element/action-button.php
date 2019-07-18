<?php
if (!is_array($config)) {
    echo $config;
    return;
}

if ($config['method'] !== 'GET') {
    echo $this->Form->postLink($config['title'], $config['url'], $config['options']);
    return;
}

echo $this->Html->link($config['title'], $config['url'], $config['options']);
