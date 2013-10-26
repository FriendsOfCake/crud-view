<div class="paging paging-centered">
	<div>
		<?= $this->Paginator->prev(__d('crud', '<<'), array(), null, array('class' => 'prev disabled')); ?>
		<?= $this->Paginator->numbers(array('separator' => '')); ?>
		<?= $this->Paginator->next(__d('crud', '>>'), array(), null, array('class' => 'next disabled')); ?>
	</div>

	<p>
		<?= $this->Paginator->counter(array(
			'format' => __d('crud', 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
		));
		?>
	</p>
</div>
