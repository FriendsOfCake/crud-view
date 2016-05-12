<?php
if (empty($bulkActions)) {
    return;
}

$submitButton = $this->Form->button(__d('crud', 'Apply'), [
    'class' => 'btn btn-primary btn-bulk-apply',
    'name' => '_bulk',
]);

echo $this->Form->input('action', [
    'empty' => __d('crud', 'Bulk Actions'),
    'label' => [
        'class' => 'col-sm-2 control-label',
        'text' => __d('crud', 'Actions'),
    ],
    'options' => $bulkActions,
    'templates' => [
        'inputContainer' => '<div class="form-group bulk-action-submit {{required}}">{{content}}{{help}}</div>',
        'select' => '<div class="col-sm-10"><select name="{{name}}"{{attrs}}>{{content}}</select>' . $submitButton . '</div>',
    ],
    'type' => 'select',
    'class' => 'no-selectize'
]);
echo $this->Form->end();
?>
