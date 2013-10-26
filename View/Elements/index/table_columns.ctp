<?php
foreach ($fields as $field => $options) {
	?>
	<td>
		<?= $this->CrudView->format($field, Hash::get($singularVar, "{$modelClass}.{$field}"), $singularVar, $modelSchema, $associations); ?>
	</td>
	<?php
}
