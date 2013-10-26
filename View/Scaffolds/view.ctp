<div class="view scaffold-view">
	<h2><?= $singularHumanName; ?>: <?= ${$viewVar}[$modelClass][$displayField];?></h2>

  <dl>
    <?php
    foreach ($fields as $field => $options) {
      if (in_array($field, array($primaryKey, $displayField))) {
        continue;
      }

      $output = $this->CrudView->relation($field, ${$viewVar}, $associations);

      if ($output) {
        echo "<dt>" . Inflector::humanize($output['alias']) . "</dt>";
        echo "<dd>";
        echo $output['output'];
        echo "&nbsp;</dd>";
      } else {
        echo "<dt>" . Inflector::humanize($field) . "</dt>";
        echo "<dd>";
        echo $this->CrudView->format($field, Hash::get(${$viewVar}, "{$modelClass}.{$field}"), ${$viewVar}, $modelSchema, $associations);
        echo "&nbsp;</dd>";
      }

    }
    ?>
  </dl>
  <?= $this->element('view/related'); ?>
</div>
