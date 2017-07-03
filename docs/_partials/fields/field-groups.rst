Grouping fields in tabs
~~~~~~~~~~~~~~~~~~~~~~~

You can group the form fields in tabs using the ``scaffold.field_groups``
configuration key:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.field_groups', [
        'First Tab Header' => ['field_1', 'field_2'],
        'Second Tab Header' => ['field_3', 'field_4'],
    ]);

If there are fields which are not listed under any group they will be
automatically shown under 1st tab with header ``Primary``. You can customize
the primary group's name using `scaffold.primary_group_name` config.

    $action = $this->Crud->action();
    $action->config('scaffold.primary_group_name', 'Key Info');
