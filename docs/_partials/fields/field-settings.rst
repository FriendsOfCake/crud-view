Setting options for specific fields
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you wish to use the default automatic field population functionality but want
to specify settings for a few of the fields, you can use the
``scaffold.field_settings`` configuration key:

.. code-block:: php

    $action = $this->Crud->action();
    $action->setConfig('scaffold.field_settings', [
        'title' => [
            // options here
        ]
    ]);
