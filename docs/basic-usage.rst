Basic Usage
===========

Getting comfortable with CRUD View usually depends on getting grips of the CRUD
plugin first. Since much of the features this plugin provides are implemented on
top of the features exposed by the CRUD plugin, much of the documentation will
just repeat what it is possible in it.

Implementing an Index List
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

Specifying the Fields to be Displayed
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you wish to control which fields should be displayed in the index table, use
the ``scaffold.fields`` and ``scaffold.fields_blacklist`` configuration keys. By
default, all fields from the table will be displayed

For example, let's avoid the ``created`` and ``modified`` fields from being
displayed in the index table:

.. code-block:: php

    <?php
    ...
    public function index()
    {
      $action = $this->Crud->action();
      $action->config('scaffold.fields_blacklist', ['created', 'modified']);
      return $this->Crud->execute();
    }

You can also be specific about the fields, and the order, in which they should
appear in the index table:

.. code-block:: php

    <?php
    ...
    public function index()
    {
      $action = $this->Crud->action();
      $action->config('scaffold.fields', ['title', 'body', 'category', 'published_time']);
      return $this->Crud->execute();
    }

Linking to Actions
~~~~~~~~~~~~~~~~~~

At the end of each row in the index table, there will be a list of actions
links, such as ``View``, ``Edit`` and ``Delete``. If you wish to control which
actions should be displayed or not, use the ``scaffold.actions`` and
``scaffold.actions_blacklist`` configurations keys.

For example, imagine we wanted to remove the ``Delete`` link from the index
table:

.. code-block:: php

    <?php
    ...
    public function index()
    {
      $action = $this->Crud->action();
      $action->config('scaffold.actions_blacklist', ['delete']);
      return $this->Crud->execute();
    }

Likewise, you can instruct the ``CrudView`` plugin on which actions should be
specifically displayed in the index view:

.. code-block:: php

    <?php
    ...
    public function index()
    {
      $action = $this->Crud->action();
      $action->config('scaffold.actions', ['view', 'add', 'edit']);
      return $this->Crud->execute();
    }

Implementing an Add Action
--------------------------

If you have read this far, you know almost everything there is to know about
configuring any type of action using ``CrudView``, but being explicit about what
is available in all of them will not hurt.

Implementing the ``Add`` action is done by adding the ``Crud.View`` action to
the ``Crud`` component configuration:

.. code-block:: php

  <?php
  public function initialize()
  {
      $this->loadComponent('Crud.Crud', [
            'actions' => [
              'Crud.Add',
              ...
            ],
            'listeners' => [
                'CrudView.View',
                'Crud.Redirect'
                'Crud.RelatedModels'
                ...
            ]
        ]);
    }

For the ``Add`` action it is recommended that you add the ``Crud.Redirect`` and
``Crud.RelatedModels`` listeners. The former will help adding more redirection
options after saving the record and the latter will send the required
information to the view so that the ``select`` widgets for associations get the
correct options.

Specifying the Fields to be Displayed
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

When adding a new record, you probably want to avoid some of the fields from
being displayed as an input in the form. Use the ``scaffold.fields`` and
``scaffold.fields_blacklist``.

For example, let's avoid having inputs for the ``created`` and ``modified``
fields in the form:


.. code-block:: php

    <?php
    ...
    public function add()
    {
      $action = $this->Crud->action();
      $action->config('scaffold.fields_blacklist', ['created', 'modified']);
      return $this->Crud->execute();
    }

It is also possible to directly specify which fields should have an input in the
form by using the ``scaffold.fields`` configuration key:

.. code-block:: php

    <?php
    ...
    public function add()
    {
      $action = $this->Crud->action();
      $action->config('scaffold.fields', ['title', 'body', 'category_id']);
      return $this->Crud->execute();
    }

You can pass attributes or change the form input type to specific fields when
using the ``scaffold.fields`` configuration key. For example, you may want to
add the ``placeholder`` property to the ``title`` input:

.. code-block:: php

    <?php
    ...
    public function add()
    {
      $action = $this->Crud->action();
      $action->config('scaffold.fields', [
        'title' => ['placeholder' => 'Insert a title here'],
        'body',
        'category_id'
      ]);
      return $this->Crud->execute();
    }
