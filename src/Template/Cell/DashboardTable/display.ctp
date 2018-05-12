<?php if (!empty($title)) :?>
    <h3><?= $title ?></h3>
<?php endif; ?>

<?php if (!empty($links)) : ?>
<table class="table">
    <tbody>
        <?php foreach ($links as $link): ?>
        <tr>
            <td><?= $this->Html->link($link->get('title'), $link->get('url'), $link->get('options')) ?></td>
            <?php if ($link->get('actions')) : ?>
            <td class="actions">
                <?php foreach ($link->get('actions') as $action) : ?>
                    <?= $this->Html->link($action->get('title'), $action->get('url'), $action->get('options')) ?>
                <?php endforeach; ?>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
