<?php
if (empty($bulkActions)) {
    return;
}
?>
<td class="bulk-action">
    <?= $this->Form->checkbox($primaryKey . '[' . $singularVar->id . ']', [
        'id' => $primaryKey . '-' . $singularVar->id,
        'checked' => false,
        'value' => $singularVar->id,
    ]) ?>
</td>
