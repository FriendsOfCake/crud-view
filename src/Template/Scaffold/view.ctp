<?php
use \Cake\Utility\Inflector;

$assocMap = isset($associations['manyToOne']) ?
    array_flip(collection($associations['manyToOne'])->extract('foreignKey')->toArray()) :
    [];
?>
<?= $this->fetch('before_view'); ?>
<div class="<?= $this->CrudView->getCssClasses(); ?>">
    <h2><?= $this->get('title');?></h2>
    <table class="table">
        <?php
        $this->CrudView->setContext(${$viewVar});
        foreach ($fields as $field => $options) {
            if (in_array($field, array($primaryKey))) {
                continue;
            }

            echo '<tr>';

            if (array_key_exists($field, $assocMap)) {
                echo "<th>" . Inflector::singularize(Inflector::humanize(Inflector::underscore($assocMap[$field]))) . "</th>";
            } else {
                echo "<th>" . \Cake\Utility\Inflector::humanize($field) . "</th>";
            }
            echo "<td>";
            echo $this->CrudView->process($field, ${$viewVar}, $options);
            echo "&nbsp;</td>";

            echo '</tr>';
        }
        ?>
    </table>
    <?= $this->element('view/related'); ?>
</div>
