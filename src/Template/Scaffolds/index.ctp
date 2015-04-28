<div class="<?= $pluralVar; ?>-<?= $this->request->action; ?> <?= $pluralVar; ?> <?= $this->request->action; ?> scaffold-view">
    <?
    if (!$this->exists('search')) {
        $this->start('search');
            echo $this->element('search');
        $this->end();
    }

    if (!$this->exists('actions')) {
        $this->start('actions');
            echo $this->element('actions');
        $this->end();
    }
    ?>
    <h2><?= $this->get('title'); ?><span class="actions"><?= $this->fetch('actions'); ?></span></h2>

    <?= $this->fetch('search'); ?>

    <hr />

    <div class="table-responsive">
        <table class="table table-hover table-condensed">
        <thead>
            <tr>
                <?php
                foreach ($fields as $field => $options) :
                    ?>
                    <th><?= $this->Paginator->sort($field, null, $options); ?></th>
                    <?php
                    endforeach;
                ?>
                <th><?= __d('crud', 'Actions'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach (${$viewVar} as $singularVar) :
                ?>
                <tr>
                    <?= $this->element('index/table_columns', compact('singularVar')); ?>
                    <td class="actions"><?= $this->element('index/table_actions', compact('singularVar')); ?></td>
                </tr>
                <?php
            endforeach;
            ?>
            </tbody>
        </table>
    </div>

    <?= $this->element('index/pagination'); ?>
</div>
