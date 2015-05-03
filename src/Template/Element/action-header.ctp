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
<h2><?= $this->get('title'); ?><span class="actions"><?= $this->fetch('actions'); ?></span></h2>
