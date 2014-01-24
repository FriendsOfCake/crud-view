<div class="btn-group">
	<button type="button" class="btn btn-default btn-xs"><?= __d('crud', 'Actions'); ?></button>
	<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
	<ul class="dropdown-menu">
		<?php
		foreach ($actions['record'] as $action) {
			echo "<li>";
			echo $this->Html->link(
				sprintf('%s %s', \Cake\Utility\Inflector::humanize($action), $singularHumanName),
				['action' => $action, $this->CrudView->fieldValue($singularVar, $primaryKey)]
			);
			echo " </li>";
		}
		?>
	</ul>
</div>
