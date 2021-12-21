<?php
$itemClass = $itemClass ?? 'nav-link';
$options = array_merge($item->getOptions(), ['class' => 'nav-link']);
?>
<?php if ($itemClass === 'nav-link') : ?>
<li class="nav-item">
<?php else : ?>
<li>
<?php endif; ?>
    <?php if ($item->getUrl() === null) : ?>
        <span class="<?= $itemClass; ?> disabled"><?= $item->getTitle(); ?></span>
    <?php else : ?>
        <?= $this->Html->link($item->getTitle(), $item->getUrl(), $options); ?>
    <?php endif; ?>
</li>
