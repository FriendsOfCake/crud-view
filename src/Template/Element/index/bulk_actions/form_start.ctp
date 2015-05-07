<?php
if (empty($bulkActions)) {
    return;
}

echo $this->Form->create(null, [
    'class' => 'bulk-actions form-horizontal'
]);
?>
