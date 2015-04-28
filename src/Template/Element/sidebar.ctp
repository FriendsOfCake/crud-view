<?php
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

?>
<div class="collapse navbar-collapse navbar-ex1-collapse navbar-left bs-sidebar">
    <nav>
        <ul class="nav nav-pills nav-stacked">
            <?php
            foreach ($tables as $table) {
                ?>
                <li><?= $this->Html->link(Inflector::humanize($table), array('controller' => $table, 'action' => 'index')); ?></li>
                <?php
            }
            ?>
        </ul>
    </nav>
</div>
