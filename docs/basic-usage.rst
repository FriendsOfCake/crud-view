Basic Usage
===========

Getting comfortable with CRUD View usually depends on getting grips of the CRUD
plugin first. Since much of the features this plugin provides are implemented on
top of the features exposed by the CRUD plugin, much of the documentation will
just repeat what it is possible in it.

Implementing an Index View
--------------------------

Rendering a list of the rows in a table is a matter of just adding the ``Crud.Index``
action to the ``Crud`` component and the ``CrudView.View`` as a listener.


.. code-block:: php

  <?php
  public function initialize()
  {
      $this->loadComponent('Crud.Crud', [
            'actions' => [
              'Crud.Index',
              ...
            ],
            'listeners' => [
                'CrudView.View',
                ...
            ]
        ]);
    }

There is no need to have an ```index()`` function in your controller. But you
can implement it to customize both the behavior and looks of the index listing
page.

.. code-block:: php
  <?php

    ...
    class ArticlesController extends AppController
    {
      public function index()
      {
        // Your customization and configuration changes here
        ...
        return $this->Crud->execute();
      }
    }

Most configuration changes need to be done by using the ``config()`` function in
the action object. The ``config()`` method can be used for both reading and
writing.

.. code-block:: php
  <?php

    ...
    public function index()
    {
      $action = $this->Crud->action(); // Gets the IndexAction object
      debug($action->config()); // Show all configuration related to this action
      return $this->Crud->execute();
    }

Below is a list of the configuration values that can be used and how they affect
the rendering of your view:

Providing Associations to be Displayed
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

By default all ``belongsTo`` and ``hasOne`` associations are fetched in the
pagination query for the index view. If you wanted to ``blacklist`` one of those
associations.

Fore example you may want to not fetch the ``Authors`` association of the
``Articles`` as you don't plan to show it in the index table:

.. code-block:: php
  <?php

    ...
    public function index()
    {
      $action = $this->Crud->action();
      $action->config('scaffold.relations_blacklist', ['Authors', ...]);
      return $this->Crud->execute();
    }

If you want to be specific about which association need to be fetched, just use
the ``scaffold.relations`` configuration key:

.. code-block:: php
  <?php

    ...
    public function index()
    {
      $action = $this->Crud->action();
      $action->config('scaffold.relations', ['Categories', 'Tags']);
      return $this->Crud->execute();
    }

Alternatively, you can use the ``Crud`` plugin's ``beforePaginate`` method to
alter the ``contain()`` list for the pagination query:

.. code-block:: php
  <?php

    ...
    public function index()
    {
      $this->Crud->on('beforePaginate', function ($event) {
        $paginationQuery  = $event->subject()->query;
        $paginationQuery->contain([
          'Categories',
          'Authors' => ['fields' => ['id', 'name']]
        ]);
      });
      return $this->Crud->execute();
    }
