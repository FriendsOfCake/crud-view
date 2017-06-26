Sidebar Navigation
==================

The "sidebar navigation" shown on the left normally shows the a link to the Crud ``index`` action for all tables. However, the sidebar navigation is just like any other menu in the system; you can provide your own menu to be rendered in its place:

.. code-block:: php

    use CrudView\Menu\MenuDivider;
    use CrudView\Menu\MenuItem;

    $action = $this->Crud->action();
    $action->config('scaffold.sidebar_navigation', [
        new MenuItem(
            'CrudView Docs',
            'https://crud-view.readthedocs.io/en/latest/contents.html',
            ['target' => 'blank']
        ),
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
    ]);

The sidebar navigation can also be completely disabled by setting the value to ``false``.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.sidebar_navigation', false);

If you wish to fallback to just showing links to Crud `index` action for all tables, you can customize this by using the ``scaffold.tables`` and ``scaffold.tables_blacklist`` crud config options:

.. code-block:: php

    $action = $this->Crud->action();

    // only show these tables
    $action->config('scaffold.tables', ['posts']);

    // do not show these tables
    $action->config('scaffold.tables_blacklist', [
        'phinxlog',
        'users',
    ]);
