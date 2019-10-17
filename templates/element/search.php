<?php
if (empty($searchInputs)) {
    return;
}
?>

<div class="search-filters">
    <?php
    $searchOptions = $searchOptions ?? [];
    $searchOptions += ['class' => 'form-inline', 'id' => 'searchFilter'];

    echo $this->Form->create(null, $searchOptions);
    echo $this->Form->hidden('_search');
    ?>

    <?= $this->Form->controls($searchInputs, ['fieldset' => false]); ?>
    <div class="form-group">
        <?= $this->Form->button(__d('crud', 'Filter results'), ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
        <?php if ($this->Search->isSearch()) : ?>
            <?= $this->Search->resetLink(__d('crud', 'Reset'), ['class' => 'btn btn-primary']) ?>
        <?php endif ?>
    </div>

    <?= $this->Form->end(); ?>
</div>
