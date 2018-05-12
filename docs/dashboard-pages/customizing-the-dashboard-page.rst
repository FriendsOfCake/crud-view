Customizing the Dashboard Page
==============================

The "dashboard" can be used to display a default landing page for CrudView-powered
admin sites. It is made of several ``\Cake\View\Cell``
instances, and can be extended to display items other than what is shipped with CrudView.

To use the "Dashboard", the custom ``DashboardAction`` needs to be mapped:

.. code-block:: php

    public function initialize()
    {
        parent::initialize();

        $this->Crud->mapAction('dashboard', 'CrudView.Dashboard');
    }

Browsing to this mapped action will result in a blank page. To customize it, a
``\CrudView\Dashboard\Dashboard`` can be configured on the ``scaffold.dashboard`` key:

.. code-block:: php

    public function dashboard()
    {
        $dashboard = new \CrudView\Dashboard\Dashboard();
        $this->Crud->action()->setConfig('scaffold.dashboard', $dashboard);
        return $this->Crud->execute();
    }


The ``\CrudView\Dashboard\Dashboard`` instance takes two arguments:

- ``title``: The title for the dashboard view. Defaults to ``Dashboard``.
- ``columns`` A number of columns to display on the view. Defaults to ``1``.

.. code-block:: php

    public function dashboard()
    {
        // setting both the title and the number of columns
        $dashboard = new \CrudView\Dashboard\Dashboard(__('Site Administration'), 12);
        $this->Crud->action()->setConfig('scaffold.dashboard', $dashboard);
        return $this->Crud->execute();
    }

Adding Cells to the Dashboard
-------------------------------

Any number of cells may be added to the Dashboard. All cells *must* extend the
``\Cake\View\Cell`` class.

Cells can be added via the ``Dashboard::addToColumn()`` method. It takes a cell
instance and a column number as arguments.

.. code-block:: php

    // assuming the `CellTrait` is in use, we can generate a cell via `$this->cell()`
    $someCell = $this->cell('SomeCell');
    $dashboard = new \CrudView\Dashboard\Dashboard(__('Site Administration'), 2);

    // add to the first column
    $dashboard->addToColumn($someCell);

    // configure the column to add to
    $dashboard->addToColumn($someCell, 2);

CrudView ships with the ``DashboardTable`` cell by default.

CrudView.DashboardTable
~~~~~~~~~~~~~~~~~~~~~~~

This can be used to display links to items in your application or offiste.

.. code-block:: php

    public function dashboard()
    {
        // setting both the title and the number of columns
        $dashboard = new \CrudView\Dashboard\Dashboard(__('Site Administration'), 1);
        $dashboard->addToColumn($this->cell('CrudView.DashboardTable', [
            'title' => 'Important Links'
        ]));

        $this->Crud->action()->setConfig('scaffold.dashboard', $dashboard);
        return $this->Crud->execute();
    }

In the above example, only a title to the ``DashboardTable``, which will
show a single subheading for your Dashboard.

In addition to showing a title, it is also possible to show a list of links. This can
be done by adding a ``links`` key with an array of ``LinkItem`` objects as the value.
Links containing urls for external websites will open in a new window by default. 

.. code-block:: php

    public function dashboard()
    {
        // setting both the title and the number of columns
        $dashboard = new \CrudView\Dashboard\Dashboard(__('Site Administration'), 1);
        $dashboard->addToColumn($this->cell('CrudView.DashboardTable', [
            'title' => 'Important Links',
            'links' => [
                new LinkItem('Example', 'https://example.com', ['target' => '_blank']),
            ],
        ]));

        $this->Crud->action()->setConfig('scaffold.dashboard', $dashboard);
        return $this->Crud->execute();
    }

There is also a special kind of ``LinkItem`` called an ``ActionLinkItem``. This
has a fourth argument is an array of ``LinkItem`` objects. It can be used to show
embedded action links on the same row.

.. code-block:: php

    public function dashboard()
    {
        $dashboard = new \CrudView\Dashboard\Dashboard(__('Site Administration'), 1);
        $dashboard->addToColumn($this->cell('CrudView.DashboardTable', [
            'title' => 'Important Links',
            'links' => [
                new ActionLinkItem('Posts', ['controller' => 'Posts'], [], [
                    new LinkItem('Add', ['controller' => 'Posts', 'action' => 'add']),
                ]),
            ],
        ]));

        $this->Crud->action()->setConfig('scaffold.dashboard', $dashboard);
        return $this->Crud->execute();
    }

.. include:: /_partials/pages/dashboard/viewblocks.rst
.. include:: /_partials/pages/dashboard/elements.rst
