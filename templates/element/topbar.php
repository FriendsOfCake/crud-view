<?php
if (empty($utilityNavigation)) {
    return;
}
?>

<ul class="nav justify-content-end" role="navigation">
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
