<?php
foreach ($tables as $table => $config) {
    ?>
    <li><?= $this->Html->link($config['title'], [
        'controller' => $config['controller'],
        'action' => $config['action'],
    ]); ?></li>
    <?php
}
?>
