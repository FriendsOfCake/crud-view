<div class="<?= $pluralVar; ?> form scaffold-view">
  <h2>Edit <?= $singularHumanName; ?>: <?= $this->data[$modelClass][$displayField];?></h2>

  <?= $this->Form->create(); ?>
  <?= $this->CrudView->redirectUrl(); ?>
  <?= $this->Form->inputs($fields, null, array('legend' => false)); ?>

  <div class="submit">
    <?php
    echo $this->Form->submit(__d('crud', 'Save'), array('name' => '_save', 'div' => false, 'class' => 'btn btn-save'));
    echo "&nbsp;";
    echo $this->Form->submit(__d('crud', 'Save and continue editing'), array('name' => '_edit', 'div' => false, 'class' => 'btn btn-alt-option btn-save-continue'));
    echo "&nbsp;";
    echo $this->Form->submit(__d('crud', 'Cancel'), array('name' => '_cancel', 'div' => false, 'class' => 'btn btn-alt-option btn-save-cancel'));
    echo "&nbsp;";
    ?>
  </div>
  <?= $this->Form->end(); ?>
</div>
