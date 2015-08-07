<?php
use \Cake\Utility\Inflector;

$assocMap = isset($associations['manyToOne']) ?
    array_flip(collection($associations['manyToOne'])->extract('foreignKey')->toArray()) :
    [];
?>
<?= $this->fetch('before_view'); ?>
<div class="<?= $this->CrudView->getCssClasses(); ?>">
    <?= $this->element('action-header') ?>
    <table class="table">
        <?php
        $this->CrudView->setContext(${$viewVar});
        foreach ($fields as $field => $options) {
            if (in_array($field, array($primaryKey))) {
                continue;
            }

            echo '<tr>';

            printf("<th>%s</th>", array_key_exists($field, $assocMap) ?
                Inflector::singularize(Inflector::humanize(Inflector::underscore($assocMap[$field]))) :
                Inflector::humanize($field));
            printf("<td>%s</td>", $this->CrudView->process($field, ${$viewVar}, $options) ?: "&nbsp;");

            echo '</tr>';
        }
        ?>
    </table>
    <?= $this->element('view/related'); ?>
</div>
