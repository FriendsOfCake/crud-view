<?php
if (empty($indexFormats)) {
    return;
}
?>
<div class="download-links">
<?= __d('crud', 'Download') ?>:
<?php foreach ($indexFormats as $formatRoute => $formatTitle) : ?>
    <?= $this->Html->link($formatTitle, $formatRoute, [
        'target' => '_blank'
    ]); ?>
<?php endforeach; ?>
</div>
