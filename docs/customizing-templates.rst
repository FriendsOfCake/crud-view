Customizing Templates
=====================

Despite ``CrudView`` being quite smart at guessing how to display your data and
having great defaults, it is very often that you need to customize the look and
feel of your Admin application.


Formatting fields
-----------------

The easiest way to modify your fields is to pass options in the ``scaffold.fields``
configuration key. ``CrudView`` makes use of the ``FormHelper::inputs()`` method
and will pass your array values as options when generating the fields. You can
pass any properties that ``FormHelper::inputs()`` supports.

.. code-block:: php

    <?php
    namespace App\Controller;

    class ArticlesController extends AppController
    {
        public function index()
        {
            $action = $this->Crud->action();
            $action->config('scaffold.fields', [
                'title',
                'thread_id' => [
                    'type' => 'text'
                ],
                'featured' => [
                    'checked' => 'checked'
                ]
            ]);
            return $this->Crud->execute();
        }
    }

Formating using a Formatter
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The most immediate changes you can do in the way data is displayed is by
applying formatters to any of your fields. Whenever you use the
``scaffold.fields`` configuration key, you can specify a formatter to be used.

.. code-block:: php

    <?php
    namespace App\Controller;

    class ArticlesController extends AppController
    {
        public function index()
        {
            $action = $this->Crud->action();
            $action->config('scaffold.fields', [
                'title',
                'published_time' => [
                    'formatter' => function ($name, Time $value) {
                        return $value->nice();
                    }
                ],
            ]);
            return $this->Crud->execute();
        }
    }

You may also specify formatters using the ``scaffold.field_settings``
configuration key. This is useful if you want to display all fields but wish
to only configure the settings for one or two.

.. code-block:: php

    <?php
    namespace App\Controller;

    class ArticlesController extends AppController
    {
        public function index()
        {
            $action = $this->Crud->action();
            $action->config('scaffold.field_settings', [
                'published_time' => [
                    'formatter' => function ($name, Time $value) {
                        return $value->nice();
                    }
                ],
            ]);
            return $this->Crud->execute();
        }
    }

Formatting with a Callable
~~~~~~~~~~~~~~~~~~~~~~~~~~

The most immediate way of formatting a field is by passing a callable function
or object. Callable functions or objects will receive 3 arguments:

* ``$name`` The name of the field to be displayed
* ``$value`` The value of the field that should be outputted
* ``$entity`` The entity object from which the field was extracted

For example, imagine that when displaying the ``published_time`` property, we
wanted to also display who approved the article:

.. code-block:: php

    $action->config('scaffold.fields', [
        'published_time' => [
            'formatter' => function ($name, $value, $entity) {
                return $value->nice() . sprintf(' (Approved by %s)', $entity->approver->name);
            }
        ]
    ]);

Formatting with an Element
~~~~~~~~~~~~~~~~~~~~~~~~~~

Sometimes you want to execute more complex formatting logic, that may involve
the use of view helpers or outputting HTML. Since building HTML outside of the
view layer is not ideal, you can use the ``element`` formatter for any of your
fields.

For example, consider this example where we want to link the ``published_time``
to the same index action by passing some search arguments:

.. code-block:: php

    $action->config('scaffold.fields', [
        // ...
        'published_time' => [
            'formatter' => 'element',
            'element' => 'search/published_time',
            'action' => 'index'
        ]
    ]);

We have instructed the formatter to use ``search/published_time`` element. Then,
it is just a matter of creating the element file with the right content:

.. code-block:: php

    // src/Template/Element/search/published_time.ctp

    echo $this->Html->link($value->timeAgoInWords(), [
        'action' => $options['action'],
        'published_time' => $value->format('Y-m-d')
    ]);

After this, when displaying the ``published_time`` field, there will the will be
a link similar to this one::

  <a href="/articles?published_time=2015-06-23">4 days ago</a>

Element files will have available at least the following variables:

* ``$value``: The value of the field
* ``$field``: The name of the field it is intended to be rendered
* ``$context``: The entity from which the value came from
* ``$options``: The array of options associated to the field as passed in ``scaffold.fields``

Changing Field Header or Label Names
------------------------------------

``CrudView`` infers the name of the field by splitting the field so that it can
be read by a human. Sometimes this is just not enough, or you may wish to show
an entirely different header in a table or label in a form.

Changing Pagination Table Headers
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

In your ``index()`` action use the ``scaffold.fields`` configuration to set the
``title`` option to any field you want their table header changed:

.. code-block:: php

    <?php
    namespace App\Controller;

    class ArticlesController extends AppController
    {
        public function index()
        {
            $action = $this->Crud->action();
            $action->config('scaffold.fields', [
                'author_id' => ['title' => 'Author Name'],
                // The rest of the fields to display here
            ]);
            return $this->Crud->execute();
        }
    }

Changing Form Input Labels
~~~~~~~~~~~~~~~~~~~~~~~~~~

In our ``add()`` and ``edit()`` actions, you can specify the input label for
title for any of the fields by using the ``scaffold.fields`` configuration

.. code-block:: php

    <?php
    namespace App\Controller;

    class ArticlesController extends AppController
    {
        public function add()
        {
            $action = $this->Crud->action();
            $action->config('scaffold.fields', [
                'author_id' => ['label' => 'Author Name'],
                // The rest of the fields to display here
            ]);
            return $this->Crud->execute();
        }
    }

Disabling the Sidebar
---------------------

There are cases where you may wish to disable the sidebar. For instance, you
may be implementing crud-view for just a single table, or have all navigation
in your header. You can disable it using the ``scaffold.disable_sidebar``
configuration key:


.. code-block:: php

    <?php
    namespace App\Controller;

    class ArticlesController extends AppController
    {
        public function beforeFilter()
        {
            parent::beforeFilter();
            $action = $this->Crud->action();
            $action->config('scaffold.disable_sidebar', false);
        }
    }

Overriding Template Parts
-------------------------

All the ``CrudView`` templates are built from several elements that can be
overridden by creating them in your own ``src/Template/Element`` folder. The
following sections will list all the elements that can be overridden for each
type of action.

In general, if you want to override a template, it is a good idea to copy the
original implementation from
``vendor/friendsofcake/crud-view/src/Template/Element``

Index Action Elements
~~~~~~~~~~~~~~~~~~~~~

search
  Create ``src/Template/Element/search.ctp`` for having full control over how
  the search filters are displayed in your pagination table. You can expect the
  ``$searchInputs`` and ``$searchOptions`` variables to be available

index/pagination
  Create ``src/Template/Element/index/pagination.ctp`` To implement your own
  pagination links and counter.

index/bulk_actions/table
  Create ``src/Template/Element/index/bulk_actions/table.ctp`` for changing how
  the bulk action inputs for the whole table. You can expect the
  ``$bulkActions``, ``$primaryKey`` and ``$singularVar`` variables to be
  available.

index/bulk_actions/record
  Create ``src/Template/Element/index/bulk_actions/record.ctp`` for changing how
  the bulk action inputs for each row are displayed. You can expect the
  ``$bulkActions``, ``$primaryKey`` and ``$singularVar`` variables to be
  available.

index/bulk_actions/form_start
  Create ``src/Template/Element/index/bulk_actions/form_start.ctp`` To customize
  the Form create call for bulk actions

index/bulk_actions/form_end
  Create ``src/Template/Element/index/bulk_actions/form_end.ctp`` To customize
  the Form end call for bulk actions
