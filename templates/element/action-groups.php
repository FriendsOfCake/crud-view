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
    <div class='btn-group' role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <?= $key ?>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <?php foreach ($group as $action => $config) : ?>
                <?php $subaction = is_array($config) ? $action : $config; ?>
                <?php if (array_key_exists($subaction, $links)) : ?>
                    <?php
                    if (isset($links[$subaction]['options']) && is_array($links[$subaction]['options'])) {
                        $links[$subaction]['options']['class'][] = 'dropdown-item';
                    } else {
                        $links[$subaction]['options'] = [
                            'class' => 'dropdown-item',
                        ];
                    }
                    ?>
                    <?= $this->element('action-button', ['config' => $links[$subaction]]); ?>
                <?php endif; ?>
            <?php endforeach; ?>
       </div>
   </div>
<?php
endforeach;
