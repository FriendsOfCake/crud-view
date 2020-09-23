<?php
if (empty($bulkActions)) {
    return;
}
?>
<th class="bulk-action">
    <?= $this->Form->checkbox($primaryKey . '[_all]') ?>
</th>
