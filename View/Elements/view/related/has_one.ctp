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
				?>
				<dt><?= Inflector::humanize($field); ?></dt>
				<dd><?= $this->CrudView->process($field, ${$viewVar}, $details); ?>&nbsp;</dd>
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
