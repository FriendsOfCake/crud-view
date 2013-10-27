<div class="paging paging-centered">
	<?= $this->Paginator->pagination(array('ul' => 'pagination')); ?>

	<p>
		<?= $this->Paginator->counter(array(
			'format' => __d('crud', 'Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
		));
		?>
	</p>
</div>
