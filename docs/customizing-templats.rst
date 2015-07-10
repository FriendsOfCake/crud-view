Customizing Templates
=====================

Despite ``CrudView`` being quite smart at guessing how to display your data and
having great defaults, it is very often that you need to customize the look and
feel of your Admin application.

Formatting fields
-----------------

The most immediate changes you can do in the way data is displayed is by
applying formatters to any of your fields. Whenever you use the
``scaffold.fields`` configuration key, you can specify a formatter to be used.

.. code-block:: php

    <?php
    ...
    class ArticlesController extends AppController
    {
      public function index()
      {
        $action = $this->Crud->action();
        $action->confg('scaffold.fields', [
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

    <?php
    ...
    $action->config('scaffold.fields', [
      ...
      'published_time' => [
        'formatter' => function ($name, $value, $entity) {
          return $value->nice() . sprintf(' (Approved by %s)', $entity->approver->name);
        }
      ]
    ]);

Formatting with an Element
--------------------------

Sometimes you want to execute more complex formatting logic, that may involve
the use of view helpers or outputting HTML. Since building HTML outside of the
view layer is not ideal, you can use the ``element`` formatter for any of your
fields.

For example, consider this example where we want to link the ``published_time``
to the same index action by passing some search arguments:

.. code-block:: php

    <?php
    ...
    $action->config('scaffold.fields', [
      ...
      'published_time' => [
        'formatter' => 'element',
        'element' => 'search/published_time'
      ]
    ]);

We have instructed the formatter to use ``search/published_time`` element. Then,
it is just a matter of creating the element file with the right content:

.. code-block:: php

    <?php
    // src/Template/Element/search/published_time.ctp

    echo $this->Html->link($value->timeAgoInWords(), [
      'action' => 'index',
      'published_time' => $value->format('Y-m-d')
    ]);

After this, when displaying the ``published_time`` field, there will the will be
a link similar to this one::

  <a href="/articles?published_time=2015-06-23">4 days ago</a>
