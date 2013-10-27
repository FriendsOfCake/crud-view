<div class="<?= $pluralVar; ?> form scaffold-view">
  <h2><?= $this->get('title');?></h2>

  <?= $this->Form->create(null, [
  	'inputDefaults' => [
  		'div' => 'form-group',
  		'label' => ['class' => 'col col-md-1 control-label'],
  		'wrapInput' => 'col col-md-11',
  		'class' => 'form-control'
  	],
  	'class' => 'well form-horizontal'
  ]);
  ?>

  <?= $this->CrudView->redirectUrl(); ?>
  <?= $this->Form->inputs($fields, null, array('legend' => false)); ?>

  <div class="form-group">
		<div class="col col-md-9 col-md-offset-3">
	    <?php
	    echo $this->Form->submit(__d('crud', 'Save'), array('name' => '_save', 'div' => false, 'class' => 'btn btn-primary'));
	    echo $this->Form->submit(__d('crud', 'Save and continue editing'), array('name' => '_edit', 'div' => false, 'class' => 'btn btn-default btn-save-continue'));
	    echo $this->Form->submit(__d('crud', 'Cancel'), array('name' => '_cancel', 'div' => false, 'class' => 'btn btn-default btn-save-cancel'));
	    ?>
	   </div>
  </div>

  <?= $this->Form->end(); ?>
</div>
