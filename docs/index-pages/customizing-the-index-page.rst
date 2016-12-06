Customizing the Index Page
==========================

Multiple Index Pages
--------------------

Sometime you may want more than one index page for a resource to represent different views to the user. If multiple index pages exist, CrudView will automatically build links at the top of the `index` page. Including multiple views is simple and requires setting the `index` view in your action.

.. code-block:: php

    $action = $this->Crud->action();
    $action->view('index');

Formatting fields
-----------------

The most immediate changes you can do in the way data is displayed is by
applying formatters to any of your fields. Whenever you use the
``scaffold.fields`` configuration key, you can specify a ``formatter`` to be used.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.fields', [
        'title',
        'published_time' => [
            'formatter' => function ($name, Time $value) {
                return $value->nice();
            }
        ],
    ]);

You may also specify formatters using the ``scaffold.field_settings``
configuration key. This is useful if you want to display all fields but wish
to only configure the settings for one or two.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.field_settings', [
        'published_time' => [
            'formatter' => function ($name, Time $value) {
                return $value->nice();
            }
        ],
    ]);

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

    $action = $this->Crud->action();
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

    $action = $this->Crud->action();
    $action->config('scaffold.fields', [
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


Index Buttons
-------------

Index Filters
-------------

Index Pagination
----------------

Custom Download Links
---------------------

Custom Blocks
-------------
