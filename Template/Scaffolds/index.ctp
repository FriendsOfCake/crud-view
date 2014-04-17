<div class="index scaffold-view">
	<h2><?= $this->get('title');?></h2>

	<table class="table table-hover">
	<thead>
		<tr>
			<?php
			foreach ($fields as $field => $options) :
				?>
				<th><?= $this->Paginator->sort($field, null, $options); ?></th>
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

	<?= $this->element('index/pagination'); ?>
</div>
