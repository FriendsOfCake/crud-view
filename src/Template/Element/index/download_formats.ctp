<?php
if (empty($indexFormats)) {
    return;
}
?>
<div class="download-links">
Download:
<?php foreach ($indexFormats as $formatTitle => $formatRoute) : ?>
    <?= $this->Html->link($formatTitle, $formatRoute, [
        'target' => '_blank'
    ]); ?>
<?php endforeach; ?>
</div>
