<div class="<?= $this->CrudView->getViewClasses(); ?>">
    <div class="row">
        <div class="col-lg-6">
            <?= $this->Form->create(${$viewVar}, ['role' => 'form']); ?>
            <?= $this->CrudView->redirectUrl(); ?>
            <?= $this->Form->inputs($fields, ['legend' => false]); ?>

            <div class="form-group">
                <div class="col pull-right">
                    <?= $this->Form->submit(__d('crud', 'Save'), ['class' => 'btn btn-primary', 'div' => false, 'name' => '_save']); ?>
                    <?= $this->Form->submit(__d('crud', 'Save & continue editing'), ['class' => 'btn btn-success btn-save-continue', 'div' => false, 'name' => '_edit']); ?>
                    <?= $this->Form->submit(__d('crud', 'Save & create new'), ['class' => 'btn btn-success', 'div' => false, 'name' => '_add']); ?>
                    <?= $this->Html->link(__d('crud', 'Back'), ['action' => 'index'], ['class' => 'btn btn-default', 'div' => false]); ?>
                </div>
            </div>

            <?= $this->Form->end(); ?>
        </div>
        <div class="col-lg-6">
        </div>
    </div>
</div>
