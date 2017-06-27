Bulk Actions
------------

The Crud plugin provides bulk actions which can be easily used with crud view.

To set up crud action in controller do something like this in initialize method.

.. code-block:: php

    $this->loadComponent('Crud.Crud', [
        'actions' => [
            'deleteAll' => [
                'className' => 'Crud.Bulk/Delete',
            ],
        ]
    ]);

Once a bulk action has been mapped, the ``scaffold.bulk_actions`` configuration
key can be specified. The ``scaffold.bulk_actions`` configuration key takes an
array of key/value pairs, where the key is the url and the value is the title.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.bulk_actions', [
        Router::url(['action' => 'deleteAll']) => __('Delete records'),
    ]);
