<?php
if (is_array($config)) {
    if ($config['method'] !== 'GET') {
        echo $this->Form->postLink(
            $config['title'],
            $config['url'],
            $config['options']
        );
    } else {
        echo $this->Html->link(
            $config['title'],
            $config['url'],
            $config['options']
        );
    }
} else {
    echo $config;
}
?>
