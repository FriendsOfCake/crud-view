<li>
    <?php if ($item->getUrl() === null) : ?>
        <span class="nav-header"><?= $item->getTitle() ?></span>
    <?php else : ?>
        <?= $this->Html->link($item->getTitle(), $item->getUrl(), $item->getOptions()); ?>
    <?php endif; ?>
</li>
