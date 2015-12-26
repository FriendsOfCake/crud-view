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

    public function initialize()
    {
        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.Index',
                // ...
            ],
            'listeners' => [
                'CrudView.View',
                // ...
            ]
        ]);
    }

There is no need to have an ```index()`` function in your controller. But you
can implement it to customize both the behavior and looks of the index listing
page.

.. code-block:: php

    <?php
    namespace App\Controller;

    class ArticlesController extends AppController
    {
        public function index()
        {
            // Your customization and configuration changes here
            return $this->Crud->execute();
        }
    }

Most configuration changes need to be done by using the ``config()`` function in
the action object. The ``config()`` method can be used for both reading and
writing.

.. code-block:: php

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

    public function index()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.relations_blacklist', ['Authors', ...]);
        return $this->Crud->execute();
    }

If you want to be specific about which association need to be fetched, just use
the ``scaffold.relations`` configuration key:

.. code-block:: php

    public function index()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.relations', ['Categories', 'Tags']);
        return $this->Crud->execute();
    }

Alternatively, you can use the ``Crud`` plugin's ``beforePaginate`` method to
alter the ``contain()`` list for the pagination query:

.. code-block:: php

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

    public function index()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.fields_blacklist', ['created', 'modified']);
        return $this->Crud->execute();
    }

You can also be specific about the fields, and the order, in which they should
appear in the index table:

.. code-block:: php

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

    public function index()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.actions_blacklist', ['delete']);
        return $this->Crud->execute();
    }

Likewise, you can instruct the ``CrudView`` plugin on which actions should be
specifically displayed in the index view:

.. code-block:: php

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

    public function initialize()
    {
        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.Add',
                // ...
            ],
            'listeners' => [
                'CrudView.View',
                'Crud.Redirect'
                'Crud.RelatedModels'
                // ...
            ]
        ]);
    }

For the ``Add`` action it is recommended that you add the ``Crud.Redirect`` and
``Crud.RelatedModels`` listeners. The former will help adding more redirection
options after saving the record and the latter will send the required
information to the view so that the ``select`` widgets for associations get the
correct options.

Implementing an Edit Action
---------------------------

Likewise, edit actions can be implemented by adding the right configuration to
the ``Crud`` component. This is the recommended configuration:

.. code-block:: php

    public function initialize()
    {
        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.Edit',
                // ...
            ],
            'listeners' => [
                'CrudView.View',
                'Crud.Redirect'
                'Crud.RelatedModels'
                // ...
            ]
        ]);
    }

As with the ``Add`` action, the ``Crud.Redirect`` and
``Crud.RelatedModels`` listeners will help handling redirection after save and
help populate the ``select`` widgets for associations.

Specifying the Fields to be Displayed
-------------------------------------

When adding or editing a record, you probably want to avoid some of the fields from
being displayed as an input in the form. Use the ``scaffold.fields`` and
``scaffold.fields_blacklist``.

For example, let's avoid having inputs for the ``created`` and ``modified``
fields in the form:


.. code-block:: php

    public function add()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.fields_blacklist', ['created', 'modified']);
        return $this->Crud->execute();
    }

It is also possible to directly specify which fields should have an input in the
form by using the ``scaffold.fields`` configuration key:

.. code-block:: php

    public function edit()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.fields', ['title', 'body', 'category_id']);
        return $this->Crud->execute();
    }

You can pass attributes or change the form input type to specific fields when
using the ``scaffold.fields`` configuration key. For example, you may want to
add the ``placeholder`` property to the ``title`` input:

.. code-block:: php

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

The configuration can be used in both ``add`` and ``edit`` actions.

Limiting the Associations Information
-------------------------------------

By default the ``RelatedModels`` listener will populate the select boxes in the
form by looking up all the records from the associated tables. For example, when
creating an Article, if you have a ``Categories`` association it will populate
the select box for the ``category_id`` field.


For a full explanation on ``RelatedModels`` please visit the `CRUD Documentation
for the RelatedModelsListener <http://crud.readthedocs.org/en/latest/listeners/related-models.html>`_.

If you want to alter the query that is used for an association in particular,
you can use the ``relatedModels`` event:

.. code-block:: php

    public function add()
    {
        $this->Crud->on('relatedModel', function(\Cake\Event\Event $event) {
            if ($event->subject->association->name() === 'Categories') {
                $event->subject->query->limit(3);
                $event->subject->query->where(['is_active' => true]);
            }
        });
        return $this->Crud->execute();
    }

The callback can be used in both ``add`` and ``edit`` actions.

Pre-Selecting Association Options
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

In order to pre-select the right association options in an ``edit`` action, for
example pre-selecting the ``category_id`` in the categories select box,
``CrudView`` will automatically fetch all associations for the entity to be
modified.

This can be wasteful at times, especially if you only allow a few associations
to be saved. For this case, you may use the ``scaffold.relations`` and
``scaffold.relations_blacklist`` to control what associations are added to
``contain()``:

.. code-block:: php

    public function edit()
    {
        $action $this->Crud->action();
        // Only fetch association info for Categories and Tags
        $action->config('scaffold.relations', ['Categories', 'Tags']);
        return $this->Crud->execute();
    }

If you choose to use ``scaffold.relations_blacklist``, then you need only
specify those association that should not be added to ``contain()``:

.. code-block:: php

    public function edit()
    {
        $action $this->Crud->action();
        // Only fetch association info for Categories and Tags
        $action->config('scaffold.relations_blacklist', ['Authors']);
        return $this->Crud->execute();
    }

Disabling the Extra Submit Buttons
----------------------------------

You may have noticed already that in the ``add`` form there are multiple submit
buttons. If you wish to only keep the "Save" button, you set the ``scaffold.disable_extra_buttons``
configuration key to ``true``:

.. code-block:: php

    public function add()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.disable_extra_buttons', true);
        return $this->Crud->execute();
    }

It is also possible to only disable a few of the extra submit buttons by using
the ``scaffold.extra_buttons_blacklist`` configuration key:

.. code-block:: php

    public function add()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.extra_buttons_blacklist', [
            'save_and_continue', // Hide the Save and Continue button
            'save_and_create', // Hide the Save and create new button
            'back', // Hide the back button
        ]);
        return $this->Crud->execute();
    }

Both settings can be used in ``add`` and ``edit`` actions.

Implementing a View Action
--------------------------

Implementing a ``View`` action, for displaying the full information for
a record, including its associations is also achieved via configuring the
``Crud`` component:

.. code-block:: php

    public function initialize()
    {
        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.View',
                // ...
            ],
            'listeners' => [
                'CrudView.View',
                // ...
            ]
        ]);
    }

For this type of action there are no extra recommended listeners that you can
apply, but there are some configuration options you can use to customize the
information that is displayed.

Specifying the Fields to be Displayed
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you wish to control which fields should be displayed in the view table, use
the ``scaffold.fields`` and ``scaffold.fields_blacklist`` configuration keys. By
default, all fields from the table will be displayed

For example, let's avoid the ``created`` and ``modified`` fields from being
displayed in the view table:

.. code-block:: php

    public function view()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.fields_blacklist', ['created', 'modified']);
        return $this->Crud->execute();
    }

You can also be specific about the fields, and the order, in which they should
appear in the index table:

.. code-block:: php

    public function view()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.fields', ['title', 'body', 'category', 'published_time']);
        return $this->Crud->execute();
    }

Providing Associations to be Displayed
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

By default all associations are fetched so they can be displayed in the view action.
Similarly to the ``Index`` action, you can use the ``scaffold.relations`` and
the ``scaffold.relations_blacklist``

Fore example you may want to not fetch the ``Authors`` association of the
``Articles`` as it may be implicit by the currently logged-in user:

.. code-block:: php

    public function view()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.relations_blacklist', ['Authors', ...]);
        return $this->Crud->execute();
    }

If you want to be specific about which association need to be fetched, just use
the ``scaffold.relations`` configuration key:

.. code-block:: php

    public function view()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.relations', ['Categories', 'Tags']);
        return $this->Crud->execute();
    }

Alternatively, you can use the ``Crud`` plugin's ``beforePaginate`` method to
alter the ``contain()`` list for the pagination query:

.. code-block:: php

    public function view()
    {
        $this->Crud->on('beforeFind', function ($event) {
            $event->subject()->query->contain([
                'Categories',
                'Authors' => ['fields' => ['id', 'name']]
            ]);
        });
        return $this->Crud->execute();
    }

Going Forward
-------------

The following chapters will show you how to customize the output of each field,
how to override parts of the templates and implementing search auto-completion.
