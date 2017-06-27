Index Buttons
-------------

By default, the included index buttons are generated based on the mapped Crud
actions. You can customize available buttons by using the ``scaffold.actions``
key:

.. code-block:: php

    $action = $this->Crud->action();

    // restrict to just the add button, which will show up globally
    $action->config('scaffold.actions', [
        'add'
    ]);

    // restrict to just the delete/edit/view actions, which are scoped to entities
    $action->config('scaffold.actions', [
        'delete',
        'edit',
        'view',
    ]);

You can also specify configuration for actions, which will be used when
generating action buttons.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.actions', [
        'duplicate' => [
            // An alternative title for the action
            'link_title' => 'Duplicate this record',

            // A url that this action should point to
            'url' => ['action' => 'jk-actually-this-action'],

            // The HTTP method to use. Defaults to GET. All others result in
            // a ``FormHelper::postLink``
            'method' => 'POST',

            // Whether to scope the action to a single entity or the entire table
            // Options: ``entity``, ``table``
            'scope' => 'entity',

            // All other options are passed in as normal to the options array
            'other' => 'options',
        ]
    ]);

Customizing primaryKey position in the url
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

For entity-scoped actions, we will append the ``primaryKey`` of the record to
the link by default:

.. code-block:: php

    $action = $this->Crud->action();

    // For the PostsController, will generate
    // /posts/translate/english/1
    $action->config('scaffold.actions', [
        'translate' => [
            'url' => ['action' => 'translate', 'english']
        ]
    ]);

We can specify the token ``:primaryKey:``. Rather than appending the
``primaryKey``, we will replace this token in the url as many times as
specified.

.. code-block:: php

    $action = $this->Crud->action();

    // For the PostsController, will generate
    // /posts/translate/1/english
    $action->config('scaffold.actions', [
        'translate' => [
            'url' => ['action' => 'translate', ':primaryKey:', 'english']
        ]
    ]);

Blacklisting Index Buttons
~~~~~~~~~~~~~~~~~~~~~~~~~~

If you wish to blacklist certain action buttons from showing up, you can use the
``scaffold.actions_blacklist`` configuration key. This can be useful when many
Crud action classes are mapped but should not all be shown on the main UI.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.actions_blacklist', ['add', 'delete']);

Action Groups
~~~~~~~~~~~~~

You can group actions together using Action Groups. This will generate a
dropdown for the group, and can be controlled by the ``scaffold.action_groups``
configuration key.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.action_groups', [
        'Actions' => [
            'view',
            'edit',
            'delete',
        ],
    ]);

All actions specified in an action group *must* be included in the
``scaffold.actions`` key.

You can specify multiple action groups:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.action_groups', [
        'Actions' => [
            'view',
            'edit',
            'delete',
        ],
        'Destructive Actions' => [
            'disable',
            'delete',
        ]
    ]);

Finally, you can also set configuration for each entry in an action group:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.action_groups', [
        'Actions' => [
            'view',
            'edit',
            'delete',
        ],
        'Translate' => [
            'english' => [
                'url' => ['action' => 'translate', 'english']
            ],
            'spanish' => [
                'url' => ['action' => 'translate', 'spanish']
            ],
        ]
    ]);
