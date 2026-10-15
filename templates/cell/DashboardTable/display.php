<?php if (!empty($title)) :?>
    <h3><?= $title ?></h3>
<?php endif; ?>

<?php if (!empty($links)) : ?>
<table class="table">
    <tbody>
        <?php foreach ($links as $link) : ?>
        <tr>
            <td><?= $this->Html->link($link->title, $link->url, $link->options) ?></td>
            <?php if ($link->actions) : ?>
            <td class="actions">
                <?php foreach ($link->actions as $action) : ?>
                    <?= $this->Html->link($action->title, $action->url, $action->options) ?>
                <?php endforeach; ?>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
