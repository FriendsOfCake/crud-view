<?= $this->fetch('before_form'); ?>

<div class="<?= $this->CrudView->getCssClasses(); ?>">
    <?= $this->element('action-header') ?>

    <?= $this->Form->create(${$viewVar}, ['role' => 'form', 'url' => $formUrl]); ?>
    <?= $this->CrudView->redirectUrl(); ?>
    <div class="row">
        <div class="col-lg-8">
            <?= $this->Form->inputs($fields, ['legend' => false]); ?>
        </div>
        <div class="col-lg-2">
            <?= $this->fetch('form.sidebar'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
           <div class="form-group">
                <div class="col pull-right">
                    <?= $this->Form->submit(__d('crud', 'Save'), ['class' => 'btn btn-primary', 'div' => false, 'name' => '_save']); ?>
                    <?= $this->Form->submit(__d('crud', 'Save & continue editing'), ['class' => 'btn btn-success btn-save-continue', 'div' => false, 'name' => '_edit']); ?>
                    <?= $this->Form->submit(__d('crud', 'Save & create new'), ['class' => 'btn btn-success', 'div' => false, 'name' => '_add']); ?>
                    <?= $this->Html->link(__d('crud', 'Back'), ['action' => 'index'], ['class' => 'btn btn-default', 'div' => false]); ?>
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>

<?= $this->fetch('after_form'); ?>
