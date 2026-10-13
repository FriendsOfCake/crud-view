<?= $this->fetch('dashboard.before'); ?>

<div class="<?= $this->CrudView->getCssClasses(); ?>">
    <?= $this->element('action-header') ?>
    <div class="row">
        <?php foreach (range(1, $dashboard->columns) as $columnNumber) : ?>
            <div class="<?= $dashboard->columnClass ?>">
                <?php foreach ($dashboard->getColumnChildren($columnNumber) as $module) : ?>
                    <?= $module ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->fetch('dashboard.after'); ?>
