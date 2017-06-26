<?php
$formSidebarExists = $this->exists('form.sidebar');
if ($this->exists('form.before_create')) {
    echo $this->fetch('form.before_create');
}
if ($this->exists('before_form')) {
    $template = 'The view block %s has been deprecated. Use %s instead.';
    $message = sprintf($template, 'before_form', 'form.before_create');
    trigger_error($message, E_USER_DEPRECATED);

    echo $this->fetch('before_form');
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
        <?php if ($this->exists('form.before_end')) : ?>
            <?= $this->fetch('form.before_end'); ?>
        <?php endif; ?>
    <?= $this->Form->end(); ?>
</div>

<?php
if ($this->exists('form.after_end')) {
    echo $this->fetch('form.after_end');
}
if ($this->exists('after_form')) {
    $template = 'The view block %s has been deprecated. Use %s instead.';
    $message = sprintf($template, 'after_form', 'form.after_end');
    trigger_error($message, E_USER_DEPRECATED);

    echo $this->fetch('after_form');
}
?>
