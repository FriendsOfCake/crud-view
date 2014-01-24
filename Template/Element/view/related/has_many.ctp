<?php
use \Cake\Utility\Inflector;

$relations = array_merge($associations['oneToMany'], $associations['hasAndBelongsToMany']);

$i = 0;
foreach ($relations as $alias => $details) :
	$otherSingularVar = Inflector::variable($alias);
	?>
	<div class="related">
		<h3><?= __d('crud', "Related %s", Inflector::humanize($details['controller'])); ?></h3>
		<?php
		if (${$viewVar}->{$alias}):
			?>
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<?php
						$otherFields = array_keys(${$viewVar}->{$alias}[0]->toArray());
						if (isset($details['with'])) {
							$index = array_search($details['with'], $otherFields);
							unset($otherFields[$index]);
						}

						foreach ($otherFields as $field) {
							echo "<th>" . Inflector::humanize($field) . "</th>";
						}
						?>
						<th class="actions">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach (${$viewVar}->{$alias} as ${$otherSingularVar}) :
						?>
						<tr>
							<?php
							foreach ($otherFields as $field) {
								?>
								<td><?= $this->CrudView->process($field, ${$otherSingularVar}); ?></td>
								<?php
							}
							?>
							<td class="actions">
								<?= $this->Html->link(__d('crud', 'View'), array('plugin' => $details['plugin'], 'controller' => $details['controller'], 'action' => 'view', ${$otherSingularVar}[$details['primaryKey']])); ?>
								<?= $this->Html->link(__d('crud', 'Edit'), array('plugin' => $details['plugin'], 'controller' => $details['controller'], 'action' => 'edit', ${$otherSingularVar}[$details['primaryKey']])); ?>
								<?= $this->Html->link(__d('crud', 'Delete'), array('plugin' => $details['plugin'], 'controller' => $details['controller'], 'action' => 'delete', ${$otherSingularVar}[$details['primaryKey']])); ?>
							</td>
						</tr>
						<?php
					endforeach;
					?>
				</tbody>
			</table>
			<?php
		endif;
		?>

		<div class="actions">
			<ul>
				<li><?= $this->CrudView->createRelationLink($alias, $details);?></li>
			</ul>
		</div>
	</div>
<?php endforeach; ?>
