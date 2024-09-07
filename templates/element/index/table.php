<?php
use Cake\Utility\Inflector;
?>
<div class="table-responsive">
    <table class="table table-hover table-sm">
    <thead>
        <tr>
            <?= $this->element('index/bulk_actions/table', compact('bulkActions', 'primaryKey', 'singularVar')); ?>

            <?php
            foreach ($fields as $field => $options) :
                ?>
                <th>
                    <?php
                    if (!empty($options['disableSort'])) {
                        echo $options['title'] ?? Inflector::humanize(str_replace('.', '_', $field));
                    } else {
                        echo $this->Paginator->sort($field, $options['title'] ?? null, $options);
                    }
                    ?>
                </th>
                <?php
            endforeach;
            ?>
            <?php if ($actionsExist = !empty($actions['entity'])) : ?>
                <th><?= __d('crud', 'Actions'); ?></th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>

        <?php
        foreach (${$viewVar} as $singularVar) :
            ?>
            <tr>
                <?= $this->element('index/bulk_actions/record', compact('bulkActions', 'primaryKey', 'singularVar')); ?>
                <?= $this->element('index/table_columns', compact('singularVar')); ?>

                <?php if ($actionsExist) : ?>
                    <td class="actions"><?= $this->element('actions', [
                        'singularVar' => $singularVar,
                        'actions' => $actions['entity'],
                    ]); ?></td>
                <?php endif; ?>
            </tr>
            <?php
        endforeach;
        ?>
        </tbody>
    </table>
</div>
