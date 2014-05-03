<?php
foreach ($fields as $field) {
	if ($field->isBlacklisted()) {
		continue;
	}

	?>
	<td><?= $this->CrudView->process($field, $singularVar); ?></td>
	<?php
}
