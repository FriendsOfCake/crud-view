<?php
if (empty($bulkActions)) {
    return;
}

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
?>
