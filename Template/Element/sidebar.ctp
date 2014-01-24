<ul class="nav navbar-nav side-nav">
	<li><?= $this->Html->link('Dashboard', '/');?></li>
	<?php
	foreach (\Cake\Core\App::objects('Model/Entity') as $model) {
		if (false !== strpos($model, 'AppModel')) {
			continue;
		}
		?>
		<li><?= $this->Html->link(\Cake\Utility\Inflector::pluralize($model), array('controller' => \Cake\Utility\Inflector::underscore(\Cake\Utility\Inflector::pluralize($model)), 'action' => 'index')); ?></li>
		<?php
	}
	?>
</ul>
