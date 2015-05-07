<?php
if (empty($bulkActions)) {
    return;
}
?>
<td class="bulk-action">
    <?= $this->Form->input($primaryKey . '[' . $singularVar->id . ']', [
        'id' => $primaryKey . '-' . $singularVar->id,
        'checked' => false,
        'label' => '',
        'type' => 'checkbox',
        'value' => $singularVar->id,
    ]); ?>
</td>
