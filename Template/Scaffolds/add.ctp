<div class="row">
	<div class="col-lg-6">
		<?php
		echo $this->Form->create(${$viewVar}, ['role' => 'form', 'class' => 'form-horizontal']);
		echo $this->Form->inputs($fields, $blacklist);
		echo $this->Form->submit('Save', ['class' => 'btn btn-success']);
		echo $this->Html->link('Back', ['action' => 'index'], ['class' => 'btn btn-default']);
		echo $this->Form->submit('Save & continue edit', ['class' => 'btn btn-default', 'name' => '_edit']);
		echo $this->Form->submit('Save & create new', ['class' => 'btn btn-default', 'name' => '_add']);
		echo $this->Form->end();
		?>
	</div>
</div>
