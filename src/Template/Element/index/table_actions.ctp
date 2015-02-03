<?php
foreach ($actions['entity'] as $action) {

	echo $this->Html->link(
		\Cake\Utility\Inflector::humanize($action),
		['action' => $action, $singularVar->id],
		['class' => 'btn btn-default']
	);

}
