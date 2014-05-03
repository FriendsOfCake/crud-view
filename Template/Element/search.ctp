<?php
if (empty($searchInputs)) {
	return;
}
?>

<div class="row-fluid">
	<?php
	$searchOptions = isset($searchOptions) ? $searchOptions : [];
	$searchOptions += ['class' => 'form-inline', 'id' => 'searchFilter'];

	echo $this->Form->create(null, $searchOptions);
	echo $this->Form->hidden('_search');
	?>

	<fieldset>
		<legend>Filter</legend>
		<?= $this->Form->inputs($searchInputs, [], ['fieldset' => false]); ?>
		<?= $this->Form->button('Filter results', ['type' => 'submit', 'class' => 'btn btn-primary']); ?>
	</fieldset>

	<?= $this->Form->end(); ?>
</div>
