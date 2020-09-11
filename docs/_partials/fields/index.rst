Customizing Fields
------------------

Fields may be specified via the ``scaffold.fields`` configuration key. By
default, this will contain a list of all columns associated with the Table being
in scope. To limit the fields used, simply specify an array of fields.

.. code-block:: php

    $action = $this->Crud->action();
    $action->setConfig('scaffold.fields', ['title', 'description']);

You may also specify an options array. For forms, *CrudView* makes use of the
``FormHelper::inputs()`` method and will pass your array values as options when
generating the output.

.. code-block:: php

    $action = $this->Crud->action();
    $action->setConfig('scaffold.fields', [
        'title',
        'thread_id' => [
            'type' => 'text'
        ],
        'featured' => [
            'checked' => 'checked'
        ]
    ]);
