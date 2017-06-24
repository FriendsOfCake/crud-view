<?= $this->fetch('before_form'); ?>
<? $formSidebarExists = $this->exists('form.sidebar'); ?>

<div class="<?= $this->CrudView->getCssClasses(); ?>">
    <?= $this->element('action-header') ?>

    <?= $this->Form->create(${$viewVar}, ['role' => 'form', 'url' => $formUrl, 'type' => 'file', 'data-dirty-check' => $enableDirtyCheck]); ?>
    <?= $this->CrudView->redirectUrl(); ?>
    <div class="row">
        <div class="col-lg-<?= $formSidebarExists ? '8' : '12' ?>">
            <?= $this->Form->inputs($fields, ['legend' => false]); ?>
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
    <?= $this->Form->end(); ?>
</div>

<?= $this->fetch('after_form'); ?>
