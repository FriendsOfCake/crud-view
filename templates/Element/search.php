<?php
if (empty($searchInputs)) {
    return;
}
?>

<div class="row-fluid search-filters">
    <?php
    $searchOptions = isset($searchOptions) ? $searchOptions : [];
    $searchOptions += ['class' => 'form-inline', 'id' => 'searchFilter'];

    echo $this->Form->create(null, $searchOptions);
    echo $this->Form->hidden('_search');
    ?>

    <fieldset>
        <?= $this->Form->controls($searchInputs, ['fieldset' => false]); ?>
        <?= $this->Form->button(__d('crud', 'Filter results'), ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
    </fieldset>

    <?= $this->Form->end(); ?>
</div>
