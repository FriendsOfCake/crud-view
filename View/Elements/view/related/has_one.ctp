<?php
if (empty($associations['hasOne'])) {
  return;
}

foreach ($associations['hasOne'] as $_alias => $_details):
		?>
<div class="related">
  <h3><?php echo __d('crud', "Related %s", Inflector::humanize($_details['controller'])); ?></h3>
  <?php if (!empty(${$viewVar}[$_alias])): ?>
    <dl>
    <?php
      $i = 0;
      $otherFields = array_keys(${$viewVar}[$_alias]);
      foreach ($otherFields as $_field) {
        echo "\t\t<dt>" . Inflector::humanize($_field) . "</dt>\n";
        echo "\t\t<dd>";
        echo $this->Crud->format($_field, Hash::get(${$viewVar}, "{$_alias}.{$_field}"), ${$viewVar});
        echo "&nbsp;</dd>\n";
      }
    ?>
    </dl>
  <?php endif; ?>
  <div class="actions">
    <ul>
    <li><?php
      echo $this->Html->link(
        __d('crud', 'View %s', Inflector::humanize(Inflector::underscore($_alias))),
        array('plugin' => $_details['plugin'], 'controller' => $_details['controller'], 'action' => 'view', ${$viewVar}[$_alias][$_details['primaryKey']])
      );
      echo "</li>\n";
      ?>
    </ul>
  </div>
</div>
<?php
endforeach;
