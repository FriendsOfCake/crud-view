<?php
use Cake\Core\Configure;
use Cake\Utility\Hash;
use CrudView\Menu\MenuDivider;
use CrudView\Menu\MenuItem;

if ($sidebarNavigation === false) {
    return;
}
?>

<div class="show navbar-ex1-collapse">
    <nav>
        <ul class="nav nav-pills flex-column bg-light">
            <?php if ($sidebarNavigation === null) : ?>
                <?= $this->cell('CrudView.TablesList', [
                    'tables' => Hash::get($actionConfig, 'scaffold.tables'),
                    'blacklist' => array_merge(
                        (array)Hash::get($actionConfig, 'scaffold.tables_blacklist'),
                        (array)Configure::read('CrudView.tablesBlacklist')
                    ),
                ]) ?>
            <?php else : ?>
                <?php
                foreach ($sidebarNavigation as $entry) {
                    if ($entry instanceof MenuItem) {
                        echo $this->element('menu/item', ['item' => $entry]);
                    } elseif ($entry instanceof MenuDivider) {
                        echo '<hr />';
                    } else {
                        throw new \Exception('Invalid Menu Item class');
                    }
                }
                ?>
            <?php endif; ?>
        </ul>
    </nav>
</div>
