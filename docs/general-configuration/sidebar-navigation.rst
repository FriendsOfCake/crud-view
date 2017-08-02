Page Sidebar Navigation
=======================

The "sidebar navigation" shown on the left normally shows the a link to the Crud
``index`` action for all tables. You can customize it using the
``scaffold.sidebar_navigation`` configuration key.

Specifying which tables to show
-------------------------------

You can specify the exact tables to show in the sidebar via the
``scaffold.tables`` configuration key:

.. code-block:: php

    // only show the posts table
    $this->Crud->action()->config('scaffold.tables', ['posts']);


Blacklisting tables
-------------------

As an alternative to whitelisting tables via ``scaffold.tables``, you can use
the ``scaffold.tables_blacklist`` configuration key to specify tables to
*exclude* from the output:

.. code-block:: php

    // do not show the ``phinxlog`` and ``users`` tables
    $this->Crud->action()->config('scaffold.tables_blacklist', [
        'phinxlog',
        'users',
    ]);

You can also specify a global tables blacklist by setting ``Crud.tablesBlacklist``
configuration key. By default the ``phinxlog`` table is blacklisted.

.. code-block:: php

    Configure::write('Crud.tablesBlacklist', ['phinxlog']);


Disabling the Sidebar Navigation
--------------------------------

The sidebar navigation can also be completely disabled by setting the value to ``false``.

.. code-block:: php

    $this->Crud->action()->config('scaffold.sidebar_navigation', false);

Custom Menus
------------

The sidebar navigation is just like any other menu in the system. You can
provide your own menu to be rendered in its place:

.. code-block:: php

    use CrudView\Menu\MenuDivider;
    use CrudView\Menu\MenuItem;

    $this->Crud->action()->config('scaffold.sidebar_navigation', [
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
