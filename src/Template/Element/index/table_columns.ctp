<?php
foreach ($fields as $field => $options) {
	?>
	<td><?= $this->CrudView->process($field, $singularVar, $options); ?></td>
	<?php
}
