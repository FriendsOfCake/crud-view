<?php
if (!$this->exists('actions')) {
    $this->start('actions');
        echo $this->element('actions', [
            'actions' => $actions['table'],
            'singularVar' => false,
        ]);
    $this->end();
}
?>
<h2><?= $this->get('title'); ?></h2>
<p class="actions"><?= $this->fetch('actions'); ?></p>
