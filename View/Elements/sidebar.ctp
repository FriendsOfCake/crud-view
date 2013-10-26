<ul class="nav navbar-nav side-nav">
	<li><?= $this->Html->link('Dashboard', '/');?></li>
	<?php
	foreach (App::objects('Model') as $model) {
		if (false !== strpos($model, 'AppModel')) {
			continue;
		}
		?>
		<li><?= $this->Html->link(Inflector::pluralize($model), array('controller' => Inflector::underscore(Inflector::pluralize($model)), 'action' => 'index')); ?></li>
		<?php
	}
	?>
</ul>
