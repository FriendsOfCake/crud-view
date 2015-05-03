<?php
$hasBulkActions = !empty($bulkActions);
?>
<div class="<?= $pluralVar; ?>-<?= $this->request->action; ?> <?= $pluralVar; ?> <?= $this->request->action; ?> scaffold-view">
    <?php
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

    <?php if ($hasBulkActions) : ?>
        <?= $this->Form->create(null, [
            'class' => 'bulk-actions form-horizontal'
        ]); ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-condensed">
        <thead>
            <tr>
                <?php if ($hasBulkActions) : ?>
                    <th class="bulk-action">
                        <?= $this->Form->input($primaryKey . '[_all]', [
                            'checked' => false,
                            'div' => false,
                            'label' => '',
                            'type' => 'checkbox',
                        ]); ?>
                    </th>
                <?php endif; ?>

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
                    <?php if ($hasBulkActions) : ?>
                        <td class="bulk-action">
                            <?= $this->Form->input($primaryKey . '[' . $singularVar->id . ']', [
                                'id' => $primaryKey . '-' . $singularVar->id,
                                'checked' => false,
                                'label' => '',
                                'type' => 'checkbox',
                                'value' => $singularVar->id,
                            ]); ?>
                        </td>
                    <?php endif; ?>
                    <?= $this->element('index/table_columns', compact('singularVar')); ?>
                    <td class="actions"><?= $this->element('index/table_actions', compact('singularVar')); ?></td>
                </tr>
                <?php
            endforeach;
            ?>
            </tbody>
        </table>
    </div>

    <?php
    if ($hasBulkActions) {
        $this->Form->templates([
            'submitContainer' => '{{content}}',
        ]);

        $submitButton = $this->Form->submit(__d('crud', 'Apply'), [
            'class' => 'btn btn-success btn-bulk-apply',
            'div' => false,
            'name' => '_bulk',
        ]);
        $this->Form->templates([
            'inputContainer' => '<div class="form-group bulk-action-submit {{required}}">{{content}}{{help}}</div>',
            'select' => '<div class="col-sm-10"><select name="{{name}}"{{attrs}}>{{content}}</select>' . $submitButton . '</div>',
        ]);

        echo $this->Form->input('action', [
            'empty' => true,
            'label' => [
                'class' => 'col-sm-2 control-label',
                'text' => 'Actions',
            ],
            'options' => $bulkActions,
            'type' => 'select',
        ]);
        echo $this->Form->end();
    }
    ?>

    <?= $this->element('index/pagination'); ?>
</div>
