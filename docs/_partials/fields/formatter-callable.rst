Formatting with a Callable
~~~~~~~~~~~~~~~~~~~~~~~~~~

.. note::

    This functionality currently only applies to ``index`` and ``view`` pages.

The most immediate way of formatting a field is by passing a callable function
or object to the ``formatter`` option. Callable functions or objects will
receive 5 arguments:

* ``$name`` The name of the field to be displayed
* ``$value`` The value of the field that should be outputted
* ``$entity`` The entity object from which the field was extracted
* ``$options`` An array of options passed to the CrudView helper when the field is being processed
* ``$View`` The view object in use during formatting

For example, imagine that when displaying the ``published_time`` property, we
wanted to also display who approved the article:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.fields', [
        'title',
        'published_time' => [
            'formatter' => function ($name, $value, $entity) {
                return $value->nice() . sprintf(' (Approved by %s)', $entity->approver->name);
            }
        ],
    ]);

You may also specify formatters using the ``scaffold.field_settings``
configuration key. This is useful if you want to display all fields but wish to
only configure the settings for one or two.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.field_settings', [
        'published_time' => [
            'formatter' => function ($name, Time $value, Entity $entity) {
                return $value->nice() . sprintf(' (Approved by %s)', $entity->approver->name);
            }
        ],
    ]);

In some cases, it may be useful to access a helper within the callable. For instance, you might want to create a link:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.fields', [
        'title',
        'external_id' => [
            'formatter' => function ($name, $value, $entity, $options, $View) {
                return $View->Html->link($name, sprintf('https://example.com/view/%d', $value));
            }
        ],
    ]);
