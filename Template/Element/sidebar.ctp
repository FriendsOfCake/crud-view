<div class="collapse navbar-collapse navbar-ex1-collapse navbar-left bs-sidebar">
	<nav>
	  <ul class="nav nav-pills nav-stacked">
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
	</nav>
</div>
