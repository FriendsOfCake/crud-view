Column sorting
~~~~~~~~~~~~~~

By default sorting links are generated for index page table's column headers
using the ``PaginatorHelper``. You can disable the link generation by using
the ``disableSort`` option:

.. code-block:: php

    $action = $this->Crud->action();
    $action->setConfig('scaffold.fields', [
        'title' => [
            'disableSort' => true,
        ]
    ]);
