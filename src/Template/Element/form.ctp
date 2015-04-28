<div class="<?= $pluralVar; ?>-<?= $this->request->action; ?> <?= $pluralVar; ?> <?= $this->request->action; ?> scaffold-view">
    <div class="row">
        <div class="col-lg-6">
            <?= $this->Form->create(${$viewVar}, ['role' => 'form']); ?>
            <?= $this->CrudView->redirectUrl(); ?>
            <?= $this->Form->inputs($fields, $blacklist, array('legend' => false)); ?>

            <div class="form-group">
                <div class="col col-md-9 col-md-offset-3">
                    <?= $this->Form->submit(__d('crud', 'Save'), ['class' => 'btn btn-primary', 'div' => false, 'name' => '_save']); ?>
                    <?= $this->Form->submit(__d('crud', 'Save & continue editing'), ['class' => 'btn btn-success btn-save-continue', 'div' => false, 'name' => '_edit']); ?>
                    <?= $this->Form->submit(__d('crud', 'Save & create new'), ['class' => 'btn btn-success', 'div' => false, 'name' => '_add']); ?>
                    <?= $this->Html->link(__d('crud', 'Back'), ['action' => 'index'], ['class' => 'btn btn-default', 'div' => false]); ?>
                </div>
            </div>

            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>
