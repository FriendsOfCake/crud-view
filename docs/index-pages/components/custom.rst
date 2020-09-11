Custom Index
============

To use a custom index element, you can set the ``scaffold.index_type`` config option.

.. code-block:: php

    $action = $this->Crud->action();
    $action->setConfig('scaffold.index_type', 'an_element');

Available Variables
-------------------

The following variables are available for use within the element:

- `fields`: List of fields to show and their options
- `actions`: A list of actions that can be displayed for the index page.
- `bulkActions`: A list of bulk actions associated with this resource
- `primaryKey`: The name of the record's primary key field.
- `singularVar`: The singular version of the resource name.
- `viewVar`: Reference to the name of the variable holding all records.
- plural of `viewVar`: The set of records.
