<?= $this->fetch('before_index'); ?>

<div class="<?= $this->CrudView->getCssClasses(); ?>">

    <?php
    if (!$this->exists('search')) {
        $this->start('search');
            echo $this->element('search');
        $this->end();
    }
    ?>
    <?= $this->element('action-header') ?>

    <?= $this->fetch('search'); ?>

    <hr />

    <?= $this->element('index/bulk_actions/form_start', compact('bulkActions')); ?>

    <div class="table-responsive">
        <table class="table table-hover table-condensed">
        <thead>
            <tr>
                <?= $this->element('index/bulk_actions/table', compact('bulkActions', 'primaryKey', 'singularVar')); ?>

                <?php
                foreach ($fields as $field => $options) :
                    ?>
                    <th>
                        <?php
                        if (!empty($options['disableSort'])) {
                            echo isset($options['title']) ? $options['title'] : \Cake\Utility\Inflector::humanize($field);
                        } else {
                            echo $this->Paginator->sort($field, isset($options['title']) ? $options['title'] : null, $options);
                        }
                        ?>
                    </th>
                    <?php
                endforeach;
                ?>
                <?php if ($actionsExist = !empty($actions['entity'])): ?>
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

                    <?php if ($actionsExist): ?>
                        <td class="actions"><?= $this->element('actions', [
                            'singularVar' => $singularVar,
                            'actions' => $actions['entity']
                        ]); ?></td>
                    <?php endif; ?>
                </tr>
                <?php
            endforeach;
            ?>
            </tbody>
        </table>
    </div>

    <?= $this->element('index/bulk_actions/form_end', compact('bulkActions')); ?>
    <?= $this->element('index/pagination'); ?>
</div>

<?= $this->fetch('after_index'); ?>
