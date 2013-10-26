<div class="index scaffold-view">
	<h2><?= $pluralHumanName; ?></h2>

	<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<?php
			foreach ($fields as $_field => $_options) :
				?>
				<th><?= $this->Paginator->sort($_field); ?></th>
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
