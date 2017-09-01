<?php
// check if groups are not empty
foreach ($groups as $key => $group) {
    $exists = false;
    foreach ($group as $action => $config) {
        $subaction = is_array($config) ? $action : $config;
        if (array_key_exists($subaction, $links)) {
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
            sprintf("%s %s", $key, $this->Html->tag('span', '', ['class' => 'caret'])),
            '#',
            ['class' => 'btn btn-default dropdown-toggle', 'escape' => false, 'data-toggle' => 'dropdown', 'aria-haspopup' => true, 'aria-expanded' => false]
        ) ?>
        <ul class="dropdown-menu pull-right">
            <?php foreach ($group as $action => $config) : ?>
                <?php $subaction = is_array($config) ? $action : $config; ?>
                <?php if (array_key_exists($subaction, $links)): ?>
                    <li><?= $this->element('action-button', ['config' => $links[$subaction]]); ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
       </ul>
   </div>
<?php
endforeach;
