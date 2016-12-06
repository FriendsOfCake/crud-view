<?= $this->fetch('before_index'); ?>

<div class="<?= $this->CrudView->getCssClasses(); ?>">

    <?php
    if (!$this->exists('search')) {
        $this->start('search');
            echo $this->element('search');
        $this->end();
    }
    ?>
    <?= $this->element('action-header') ?>

    <?= $this->fetch('search'); ?>

    <hr />

    <?= $this->element('index/bulk_actions/form_start', compact('bulkActions')); ?>

    <?= $this->element('index/table', [
        'fields' => $fields,
        'actions' => $actions,
        'bulkActions' => $bulkActions,
        'primaryKey' => $primaryKey,
        'singularVar' => $singularVar,
        'viewVar' => $viewVar,
        $viewVar => ${$viewVar},
    ])?>

    <?= $this->element('index/bulk_actions/form_end', compact('bulkActions')); ?>
    <?= $this->element('index/pagination'); ?>
</div>

<?= $this->fetch('after_index'); ?>
