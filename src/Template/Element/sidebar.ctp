<?php
use Cake\Core\App;
use Cake\Utility\Inflector;

?>
<div class="collapse navbar-collapse navbar-ex1-collapse navbar-left bs-sidebar">
    <nav>
        <ul class="nav nav-pills nav-stacked">
            <?php
            foreach (App::objects('Model/Entity') as $model) {
                if (false !== strpos($model, 'AppModel')) {
                    continue;
                }
                ?>
                <li><?= $this->Html->link(Inflector::pluralize($model), array('controller' => Inflector::underscore(Inflector::pluralize($model)), 'action' => 'index')); ?></li>
                <?php
            }
            ?>
        </ul>
    </nav>
</div>
