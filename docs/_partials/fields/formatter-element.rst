Formatting with an Element
~~~~~~~~~~~~~~~~~~~~~~~~~~

.. note::

    This functionality currently only applies to ``index`` and ``view`` pages.

Sometimes you want to execute more complex formatting logic, that may involve
the use of view helpers or outputting HTML. Since building HTML outside of the
view layer is not ideal, you can use the ``element`` formatter for any of your
fields.

For example, consider this example where we want to link the ``published_time``
to the same index action by passing some search arguments:

.. code-block:: php

    $action = $this->Crud->action();
    $action->setConfig('scaffold.fields', [
        'published_time' => [
            'formatter' => 'element',
            'element' => 'search/published_time',
            'action' => 'index'
        ]
    ]);

We have instructed the formatter to use ``search/published_time`` element. Then,
it is just a matter of creating the element file with the right content:

.. code-block:: php

    // templates/element/search/published_time.ctp

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
