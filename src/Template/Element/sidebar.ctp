<?php
if ($sidebarNavigation === false) {
    return;
}
?>

<div class="collapse navbar-collapse navbar-ex1-collapse navbar-left bs-sidebar">
    <nav>
        <ul class="nav nav-pills nav-stacked">
            <?php if ($sidebarNavigation === null) : ?>
                <?= $this->cell('CrudView.TablesList', [
                    'tables' => \Cake\Utility\Hash::get($actionConfig, 'scaffold.tables'),
                    'blacklist' => \Cake\Utility\Hash::get($actionConfig, 'scaffold.tables_blacklist')
                ]) ?>

            <?php else : ?>
                <?php
                    foreach ($sidebarNavigation as $entry) {
                        if ($entry instanceof \CrudView\Menu\MenuItem) {
                            echo $this->element('menu/item', ['item' => $entry]);
                        } elseif ($entry instanceof \CrudView\Menu\MenuDivider) {
                            echo '<hr />';
                        } else {
                            throw new Exception('Invalid Menu Item class');
                        }
                    }
                ?>
            <?php endif; ?>
        </ul>
    </nav>
</div>
