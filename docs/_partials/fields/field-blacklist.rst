Removing Fields from output
~~~~~~~~~~~~~~~~~~~~~~~~~~~

You may also use the ``scaffold.fields_blacklist`` configuration key to remove
fields from the output if you are using the default automatic field population
functionality:

.. code-block:: php

    $action = $this->Crud->action();
    $action->setConfig('scaffold.fields_blacklist', ['created', 'modified']);
