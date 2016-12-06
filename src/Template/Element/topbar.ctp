<?php
if (empty($utilityNavigation)) {
    return;
}
?>

<ul class="nav navbar-nav navbar-right navbar-user">
    <?php
    foreach ($utilityNavigation as $entry) {
        if ($entry instanceof \CrudView\Menu\MenuItem) {
            echo $this->element('menu/item', ['item' => $entry]);
        } elseif ($entry instanceof \CrudView\Menu\MenuDropdown) {
            echo $this->element('menu/dropdown', ['dropdown' => $entry]);
        } else {
            throw new Exception('Invalid Menu Item class');
        }
    }
    ?>
</ul>
