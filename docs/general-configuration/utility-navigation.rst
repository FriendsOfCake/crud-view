Utility Navigation
==================

The "utility navigation" shown at the top right normally shows the current user
and a link to log out. It can be controlled via the
``scaffold.utility_navigation`` configuration key

Disabling the Utility Navigation
--------------------------------

The utility navigation can also be completely disabled by setting the value to ``false``.

.. code-block:: php

    $this->Crud->action()->config('scaffold.utility_navigation', false);

Custom Menus
------------

The utility navigation is just like any other menu in the system. You can
provide your own menu to be rendered in its place:

.. code-block:: php

    use CrudView\Menu\MenuDivider;
    use CrudView\Menu\MenuDropdown;
    use CrudView\Menu\MenuItem;

    $action = $this->Crud->action();
    $action->config('scaffold.utility_navigation', [
        new MenuItem(
            'CrudView Docs',
            'https://crud-view.readthedocs.io/en/latest/contents.html',
            ['target' => 'blank']
        ),
        new MenuDropdown(
            'John Smith',
            [
                new MenuItem(
                    'Profile',
                    ['controller' => 'Users', 'action' => 'profile']
                ),
                new MenuItem(
                    'Inbox',
                    ['controller' => 'Users', 'action' => 'inbox']
                ),
                new MenuItem(
                    'Settings',
                    ['controller' => 'Site', 'action' => 'settings']
                ),
                new MenuDivider(),
                new MenuItem(
                    'Log Out',
                    ['controller' => 'Users', 'action' => 'logout']
                )
            ]
        )
    ]);
