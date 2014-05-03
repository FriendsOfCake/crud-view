<div class="row">
	<div class="col-md-12">

		<?php
		if ($this->Paginator->hasPage(2)) {
			?>
			<ul class="pagination">
				<?= $this->Paginator->prev('PREV'); ?>
				<?= $this->Paginator->numbers(); ?>
				<?= $this->Paginator->next('NEXT'); ?>
			</ul>
			<?php
		}
		?>

		<br />

		<?= $this->Paginator->counter('Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total.'); ?>
	</div>
</div>
