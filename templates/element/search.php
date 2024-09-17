<?php
if (empty($searchInputs)) {
    return;
}
?>

<div class="search-filters">
    <?php
    $searchOptions = $searchOptions ?? [];
    $searchOptions += ['align' => 'inline', 'id' => 'searchFilter'];

    echo $this->Form->create(null, $searchOptions);
    echo $this->Form->hidden('_search');
    ?>

    <?= $this->Form->controls($searchInputs, ['fieldset' => false]); ?>
    <?= $this->Form->button(__d('crud', 'Filter results'), ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
    <?php if ($this->Search->isSearch()) : ?>
        <div class="col-auto">
            <?= $this->Search->resetLink(__d('crud', 'Reset'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php endif ?>

    <?= $this->Form->end(); ?>
</div>
