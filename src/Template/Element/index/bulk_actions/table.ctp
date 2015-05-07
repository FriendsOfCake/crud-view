<?php
if (empty($bulkActions)) {
    return;
}
?>
<th class="bulk-action">
    <?= $this->Form->input($primaryKey . '[_all]', [
        'checked' => false,
        'div' => false,
        'label' => '',
        'type' => 'checkbox',
    ]); ?>
</th>
