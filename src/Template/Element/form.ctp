<?= $this->fetch('before_form'); ?>

<div class="<?= $this->CrudView->getCssClasses(); ?>">
    <?= $this->element('action-header') ?>

    <?= $this->Form->create(${$viewVar}, ['role' => 'form', 'url' => $formUrl]); ?>
    <?= $this->CrudView->redirectUrl(); ?>
    <div class="row">
        <div class="col-lg-<?= $this->exists('form.sidebar') ? '8' : '12' ?>">
            <?= $this->Form->inputs($fields, ['legend' => false]); ?>
        </div>

        <?php if ($this->exists('form.sidebar')) : ?>
            <div class="col-lg-2">
                <?= $this->fetch('form.sidebar'); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-lg-<?= $this->exists('form.sidebar') ? '8' : '12' ?>">
           <div class="form-group">
                <div class="col pull-right">
                    <?php
                        echo $this->Form->submit(__d('crud', 'Save'), ['class' => 'btn btn-primary', 'div' => false, 'name' => '_save']);
                        if (empty($disableExtraButtons)) {
                            if (!in_array('save_and_continue', $extraButtonsBlacklist)) {
                                echo $this->Form->submit(__d('crud', 'Save & continue editing'), ['class' => 'btn btn-success btn-save-continue', 'div' => false, 'name' => '_edit']);
                            }
                            if (!in_array('save_and_create', $extraButtonsBlacklist)) {
                                echo $this->Form->submit(__d('crud', 'Save & create new'), ['class' => 'btn btn-success', 'div' => false, 'name' => '_add']);
                            }
                            if (!in_array('back', $extraButtonsBlacklist)) {
                                echo $this->Html->link(__d('crud', 'Back'), ['action' => 'index'], ['class' => 'btn btn-default', 'div' => false]);
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?= $this->Form->end(); ?>
</div>

<?= $this->fetch('after_form'); ?>
