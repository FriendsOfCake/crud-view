<div class="collapse navbar-collapse navbar-ex1-collapse navbar-left bs-sidebar">
    <nav>
        <ul class="nav nav-pills nav-stacked">
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
        </ul>
    </nav>
</div>
