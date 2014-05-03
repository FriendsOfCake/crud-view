<div class="index scaffold-view">
	<h2><?= $this->get('title');?></h2>

	<?php
	$this->startIfEmpty('search');
		echo $this->element('search');
	$this->end();

	echo $this->fetch('search');
	?>

	<br />

	<div class="table-responsive">
		<table class="table table-hover table-condensed">
		<thead>
			<tr>
				<?php
				foreach ($fields->paginate() as $field) :
					?>
					<th><?= $this->Paginator->sort($field->name(), $field->alias(), $field->paginate()); ?></th>
					<?php
					endforeach;
				?>
				<th><?= __d('crud', 'Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach (${$viewVar} as $singularVar) :
				?>
				<tr>
					<?= $this->element('index/table_columns', compact('singularVar')); ?>
					<td class="actions"><?= $this->element('index/table_actions', compact('singularVar')); ?></td>
				</tr>
				<?php
			endforeach;
			?>
			</tbody>
		</table>
	</div>

	<?= $this->element('index/pagination'); ?>
</div>
