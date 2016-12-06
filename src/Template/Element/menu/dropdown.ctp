<li class="dropdown alerts-dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $dropdown->getTitle() ?></a>
    <ul class="dropdown-menu">
        <?php
        foreach ($dropdown->getEntries() as $entry) {
            if ($entry instanceof \CrudView\Menu\MenuItem) {
                echo $this->element('menu/item', ['item' => $entry]);
            } elseif ($entry instanceof \CrudView\Menu\MenuDivider) {
                echo $this->element('menu/divider');
            } else {
                throw new Exception('Invalid Menu Item class');
            }
        }
        ?>
    </ul>
</li>
