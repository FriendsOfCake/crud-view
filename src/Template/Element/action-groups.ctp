<?php
// check if groups are not empty
foreach ($groups as $key => $group) {
    $exists = false;
    foreach ($group as $action) {
        if (array_key_exists($action, $links)) {
            $exists = true;
        }
    }
    if (!$exists) {
        unset($groups[$key]);
    }
}
?>

<?php foreach ($groups as $key => $group) : ?>
    <div class='btn-group'>
        <?= $this->Html->link(
            sprintf("%s %s", $key, $this->Html->tag('span', null, ['class' => 'caret'])),
            '#',
            ['class' => 'btn btn-default dropdown-toggle', 'escape' => false, 'data-toggle' => 'dropdown', 'aria-haspopup' => true, 'aria-expanded' => false]);
        ?>
        <ul class="dropdown-menu pull-right">
            <?php foreach ($group as $subaction) : ?>
                <?php if (array_key_exists($subaction, $links)): ?>
                    <li><?= $this->element('action-button', ['config' => $links[$subaction]]); ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
       </ul>
   </div>
<?php endforeach; ?>

