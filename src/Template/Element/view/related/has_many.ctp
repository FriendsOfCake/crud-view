<?php
use Cake\Utility\Inflector;

if (empty($associations['manyToMany'])) {
    $associations['manyToMany'] = [];
}

if (empty($associations['oneToMany'])) {
    $associations['oneToMany'] = [];
}
$relations = array_merge($associations['oneToMany'], $associations['manyToMany']);

$i = 0;
foreach ($relations as $alias => $details):
    $otherSingularVar = $details['propertyName'];
    ?>
    <div class="related">
        <h3><?= __d('crud', 'Related {0}', [Inflector::humanize($details['controller'])]); ?></h3>
        <div class="actions-wrapper">
            <?= $this->CrudView->createRelationLink($alias, $details, array('class' => 'btn btn-default'));?>
        </div>
        <?php
        if (${$viewVar}->{$details['entities']}):
            ?>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <?php
                        $otherFields = array_keys(${$viewVar}->{$details['entities']}[0]->toArray());
                        if (isset($details['with'])) {
                            $index = array_search($details['with'], $otherFields);
                            unset($otherFields[$index]);
                        }

                        foreach ($otherFields as $field) {
                            echo "<th>" . Inflector::humanize($field) . "</th>";
                        }
                        ?>
                        <th class="actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach (${$viewVar}->{$details['entities']} as ${$otherSingularVar}) :
                        ?>
                        <tr>
                            <?php
                            foreach ($otherFields as $field) {
                                ?>
                                <td><?= $this->CrudView->process($field, ${$otherSingularVar}); ?></td>
                                <?php
                            }
                            ?>
                            <td class="actions">
                                <?= $this->Html->link(__d('crud', 'View'), array('plugin' => $details['plugin'], 'controller' => $details['controller'], 'action' => 'view', ${$otherSingularVar}[$details['primaryKey']]), array('class' => 'btn btn-default')); ?>
                                <?= $this->Html->link(__d('crud', 'Edit'), array('plugin' => $details['plugin'], 'controller' => $details['controller'], 'action' => 'edit', ${$otherSingularVar}[$details['primaryKey']]), array('class' => 'btn btn-default')); ?>
                                <?= $this->Html->link(__d('crud', 'Delete'), array('plugin' => $details['plugin'], 'controller' => $details['controller'], 'action' => 'delete', ${$otherSingularVar}[$details['primaryKey']]), array('class' => 'btn btn-default')); ?>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
            <?php
        endif;
        ?>
    </div>
<?php endforeach; ?>
