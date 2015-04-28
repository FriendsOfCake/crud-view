<?php
use Cake\Utility\Inflector;

?>
<div class="<?= $pluralVar; ?>-<?= $this->request->action; ?> <?= $pluralVar; ?> <?= $this->request->action; ?> scaffold-view">
    <h2><?= $this->get('title');?></h2>

    <dl>
        <?php
        $this->CrudView->setContext(${$viewVar});
        foreach ($fields as $field => $options) {
            if (in_array($field, array($primaryKey))) {
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
                echo $this->CrudView->process($field, ${$viewVar}, $options);
                echo "&nbsp;</dd>";
            }
        }
        ?>
    </dl>
    <?= $this->element('view/related'); ?>
</div>
