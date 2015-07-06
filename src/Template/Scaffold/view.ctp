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
            $output = $this->CrudView->relation($field, ${$viewVar}, $associations);

            if ($output) {
                echo "<th>" . \Cake\Utility\Inflector::humanize($output['alias']) . "</th>";
                echo "<td>";
                echo $output['output'];
                echo "&nbsp;</td>";
            } else {
                echo "<th>" . \Cake\Utility\Inflector::humanize($field) . "</th>";
                echo "<td>";
                echo $this->CrudView->process($field, ${$viewVar}, $options);
                echo "&nbsp;</td>";
            }
            echo '</tr>';
        }
        ?>
    </table>
    <?= $this->element('view/related'); ?>
</div>
<?= $this->fetch('after_view'); ?>
