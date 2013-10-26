<div class="btn-group">
	<button type="button" class="btn btn-default"><?= __d('crud', 'Actions'); ?></button>
	<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
	<ul class="dropdown-menu">
		<?php
		foreach ($actions['record'] as $_action) {
			echo "<li>";
			echo $this->Html->link(
				sprintf('%s %s', Inflector::humanize($_action), $singularHumanName),
				array('action' => $_action, $singularVar[$modelClass][$primaryKey])
			);
			echo " </li>";
		}
		?>
	</ul>
</div>
