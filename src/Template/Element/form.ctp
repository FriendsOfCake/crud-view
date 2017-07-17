<?php
use Cake\Utility\Text;

$formSidebarExists = $this->exists('form.sidebar');
if ($this->exists('form.before_create')) {
    echo $this->fetch('form.before_create');
}
?>

<div class="<?= $this->CrudView->getCssClasses(); ?>">
    <?= $this->element('action-header') ?>

    <?= $this->Form->create(${$viewVar}, ['role' => 'form', 'url' => $formUrl, 'type' => 'file', 'data-dirty-check' => $formEnableDirtyCheck]); ?>
        <?php if ($this->exists('form.after_create')) : ?>
            <?= $this->fetch('form.after_create'); ?>
        <?php endif; ?>
        <?= $this->CrudView->redirectUrl(); ?>
        <div class="row">
            <div class="col-lg-<?= $formSidebarExists ? '8' : '12' ?>">
                <?php if ($formTabGroups) : ?>
                    <?= $this->element('form/tabs') ?>
                <?php else : ?>
                    <?= $this->Form->inputs($fields, ['legend' => false]) ?>
                <?php endif ?>
            </div>

            <?php if ($formSidebarExists) : ?>
                <div class="col-lg-2">
                    <?= $this->fetch('form.sidebar'); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-lg-<?= $formSidebarExists ? '8' : '12' ?>">
                <div class="form-group">
                    <?= $this->element('form/buttons') ?>
                </div>
            </div>
        </div>
        <?php if ($this->exists('form.before_end')) : ?>
            <?= $this->fetch('form.before_end'); ?>
        <?php endif; ?>
    <?= $this->Form->end(); ?>
</div>

<?php
if ($this->exists('form.after_end')) {
    echo $this->fetch('form.after_end');
}
?>
