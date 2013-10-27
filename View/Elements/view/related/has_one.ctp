<?php
if (empty($associations['hasOne'])) {
	return;
}

foreach ($associations['hasOne'] as $alias => $details):
	?>
<div class="related">
	<h3><?= __d('crud', "Related %s", Inflector::humanize($details['controller'])); ?></h3>
	<?php
	if (!empty(${$viewVar}[$alias])) :
		?>
		<dl>
			<?php
			$i = 0;
			$otherFields = array_keys(${$viewVar}[$alias]);
			foreach ($otherFields as $field) {
				echo "\t\t<dt>" . Inflector::humanize($field) . "</dt>\n";
				echo "\t\t<dd>";
				echo $this->CrudView->process($field, ${$viewVar}[$alias], $_details);
				echo "&nbsp;</dd>\n";
			}
		?>
		</dl>
	<?php endif; ?>
	<div class="actions">
		<ul>
		<li><?php
			echo $this->Html->link(
				__d('crud', 'View %s', Inflector::humanize(Inflector::underscore($_alias))),
				array('plugin' => $_details['plugin'], 'controller' => $_details['controller'], 'action' => 'view', ${$viewVar}[$_alias][$_details['primaryKey']])
			);
			echo "</li>\n";
			?>
		</ul>
	</div>
</div>
<?php
endforeach;
