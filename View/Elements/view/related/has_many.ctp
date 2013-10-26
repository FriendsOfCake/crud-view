<?php
$relations = array_merge($associations['hasMany'], $associations['hasAndBelongsToMany']);
$i = 0;
foreach ($relations as $_alias => $_details):
  $otherSingularVar = Inflector::variable($_alias);
?>
  <div class="related">
    <h3><?php echo __d('crud', "Related %s", Inflector::humanize($_details['controller'])); ?></h3>
    <?php if (!empty(${$viewVar}[$_alias])): ?>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
        <?php
            $otherFields = array_keys(${$viewVar}[$_alias][0]);
            if (isset($_details['with'])) {
              $index = array_search($_details['with'], $otherFields);
              unset($otherFields[$index]);
            }
            foreach ($otherFields as $_field) {
              echo "\t\t<th>" . Inflector::humanize($_field) . "</th>\n";
            }
        ?>
            <th class="actions">Actions</th>
          </tr>
        </thead>
        <tbody>
    <?php
        $i = 0;
        foreach (${$viewVar}[$_alias] as ${$otherSingularVar}):
          echo "<tr>";

          foreach ($otherFields as $_field) {
            echo "<td>" . ${$otherSingularVar}[$_field] . "</td>";
          }

          echo "<td class=\"actions\">";
          echo $this->Html->link(
            __d('crud', 'View'),
            array('plugin' => $_details['plugin'], 'controller' => $_details['controller'], 'action' => 'view', ${$otherSingularVar}[$_details['primaryKey']])
          );
          echo ' &nbsp; ';
          echo $this->Html->link(
            __d('crud', 'Edit'),
            array('plugin' => $_details['plugin'], 'controller' => $_details['controller'], 'action' => 'edit', ${$otherSingularVar}[$_details['primaryKey']])
          );
          echo ' &nbsp; ';
          echo $this->Html->link(
            __d('crud', 'Delete'),
            array('plugin' => $_details['plugin'], 'controller' => $_details['controller'], 'action' => 'delete', ${$otherSingularVar}[$_details['primaryKey']])
          );
          echo "</td>";
        echo "</tr>";
        endforeach;
    ?>
        </tbody>
      </table>
    <?php endif; ?>
    <div class="actions">
      <ul>
        <li><?php echo $this->Html->link(
          __d('crud', "Add %s", Inflector::humanize(Inflector::underscore($_alias))),
          array('plugin' => $_details['plugin'], 'controller' => $_details['controller'], 'action' => 'add', '?' => array($_details['foreignKey'] => $primaryKeyValue))
        ); ?> </li>
      </ul>
    </div>
  </div>
<?php endforeach; ?>
