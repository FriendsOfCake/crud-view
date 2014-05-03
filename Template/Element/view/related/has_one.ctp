<?php
if (empty($associations['oneToOne'])) {
	return;
}

use \Cake\Utility\Inflector;

foreach ($associations['oneToOne'] as $alias => $details):
	$alias = Inflector::singularize($alias);
	?>
<div class="related">
	<h3><?= __d('crud', "Related %s", Inflector::humanize($details['controller'])); ?></h3>

	<?php
	if (!empty(${$viewVar}->{$alias})) :
		?>
		<dl>
			<?php
			$i = 0;
			$otherFields = array_keys(${$viewVar}->{$alias}->toArray());
			foreach ($otherFields as $field) {
				?>
				<dt><?= Inflector::humanize($field); ?></dt>
				<dd><?= $this->CrudView->process($field, ${$viewVar}->{$alias}, $details); ?>&nbsp;</dd>
				<?php
			}
		?>
		</dl>
		<?php
	endif;
	?>

	<div class="actions">
		<ul>
			<li><?= $this->Html->link(
				__d('crud', 'View %s', Inflector::humanize(Inflector::underscore($alias))),
				array('plugin' => $details['plugin'], 'controller' => $details['controller'], 'action' => 'view', ${$viewVar}[$alias][$details['primaryKey']])
			);
			?>
			</li>
		</ul>
	</div>
</div>
<?php
endforeach;
