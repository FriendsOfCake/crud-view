<?php
if (empty($searchInputs)) {
    return;
}
?>

<div class="search-filters mb-3">
    <?php
    $searchOptions = $searchOptions ?? [];
    $searchOptions += ['align' => 'inline', 'id' => 'searchFilter'];
    ?>

    <?= $this->Form->create(null, $searchOptions) ?>
    <?= $this->Form->hidden('_search') ?>

    <?= $this->Form->controls($searchInputs, ['fieldset' => false]); ?>

    <div class="col-auto">
        <?= $this->Form->button(__d('crud', 'Filter results'), ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
        <?php if ($this->Search->isSearch()) : ?>
            <?= $this->Search->resetLink(__d('crud', 'Reset'), ['class' => 'btn btn-secondary']) ?>
        <?php endif ?>
    </div>

    <?= $this->Form->end(); ?>
</div>
