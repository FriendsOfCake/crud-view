<?php
if (empty($indexFormats)) {
    return;
}
?>
<div class="download-links">
<?= __d('crud', 'Download') ?>:
<?php foreach ($indexFormats as $indexFormat) : ?>
    <?= $this->Html->link($indexFormat['title'], $indexFormat['url'], [
        'target' => '_blank'
    ]); ?>
<?php endforeach; ?>
</div>
