<?php

use CrudView\Menu\MenuItem;

foreach ($tables as $entry) {
    $menu = new MenuItem($entry['title'], ['controller' => $entry['controller'], 'action' => 'index']);
    echo $this->element('menu/item', ['item' => $menu, 'itemClass' => 'nav-link']);
}
