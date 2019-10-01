<?php
use Cake\Utility\Inflector;

if (empty($associations['oneToOne'])) {
    return;
}

foreach ($associations['oneToOne'] as $alias => $details):
    $alias = $details['propertyName'];
    ?>
<div class="related">
    <h3><?= __d('crud', 'Related {0}', [Inflector::humanize($details['controller'])]); ?></h3>

    <div class="actions-wrapper">
        <?= $this->Html->link(
            __d('crud', 'View {0}', [Inflector::humanize(Inflector::underscore($alias))]),
            array('plugin' => $details['plugin'], 'controller' => $details['controller'], 'action' => 'view', ${$viewVar}[$alias][$details['primaryKey']]),
            array('class' => 'btn btn-secondary')
        ); ?>
    </div>

    <?php
    if (!empty(${$viewVar}->{$alias})) :
        ?>
        <dl>
            <?php
            $i = 0;
            $otherFields = array_keys(${$viewVar}->{$alias}->toArray());
            foreach ($otherFields as $field) {
                ?>
                <dt><?= Inflector::humanize($field); ?></dt>
                <dd><?= $this->CrudView->process($field, ${$viewVar}->{$alias}, $details); ?>&nbsp;</dd>
                <?php
            }
        ?>
        </dl>
        <?php
    endif;
    ?>
</div>
<?php
endforeach;
