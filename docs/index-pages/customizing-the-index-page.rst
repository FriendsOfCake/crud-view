Customizing the Index Page
==========================

Multiple Index Pages
--------------------

Sometime you may want more than one index page for a resource to represent
different views to the user. If multiple index pages exist, CrudView will
automatically build links at the top of the `index` page. Including multiple
views is simple and requires setting the `index` view in your action.

.. code-block:: php

    $action = $this->Crud->action();
    $action->view('index');

Formatting fields
-----------------

The most immediate changes you can do in the way data is displayed is by
applying formatters to any of your fields. Whenever you use the
``scaffold.fields`` configuration key, you can specify a ``formatter`` to be
used.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.fields', [
        'title',
        'published_time' => [
            'formatter' => function ($name, Time $value) {
                return $value->nice();
            }
        ],
    ]);

You may also specify formatters using the ``scaffold.field_settings``
configuration key. This is useful if you want to display all fields but wish to
only configure the settings for one or two.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.field_settings', [
        'published_time' => [
            'formatter' => function ($name, Time $value) {
                return $value->nice();
            }
        ],
    ]);

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

    $action = $this->Crud->action();
    $action->config('scaffold.fields', [
        'published_time' => [
            'formatter' => function ($name, $value, $entity) {
                return $value->nice() . sprintf(' (Approved by %s)', $entity->approver->name);
            }
        ]
    ]);

Formatting with an Element
~~~~~~~~~~~~~~~~~~~~~~~~~~

Sometimes you want to execute more complex formatting logic, that may involve
the use of view helpers or outputting HTML. Since building HTML outside of the
view layer is not ideal, you can use the ``element`` formatter for any of your
fields.

For example, consider this example where we want to link the ``published_time``
to the same index action by passing some search arguments:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.fields', [
        'published_time' => [
            'formatter' => 'element',
            'element' => 'search/published_time',
            'action' => 'index'
        ]
    ]);

We have instructed the formatter to use ``search/published_time`` element. Then,
it is just a matter of creating the element file with the right content:

.. code-block:: php

    // src/Template/Element/search/published_time.ctp

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

Index Filters
-------------

Index Finder Scopes
-------------------

In some cases, it is helpful to show quick links to pre-filtered datasets.
Rather than force users to select all the filters, CrudView enables the ability
to display "Finder Scope" links via the ``scaffold.index_finder_scopes``
configuration key. These are output below the action header, above the data that
is being paginated.

The ``scaffold.index_finder_scopes`` option takes an array of finder scope data.
Each sub-array should contain ``title`` and ``finder`` parameters.

.. code-block:: php

    $this->Crud->action()->config('scaffold.index_finder_scopes', [
            [
                'title' => __('All'),
                'finder' => 'all',
            ],
            [
                'title' => __('Active'),
                'finder' => 'active',
            ],
            [
                'title' => __('Inactive'),
                'finder' => 'inactive',
            ],
    ]);

The ``all`` finder scope is special. This scope will be displayed by default,
and should always be included in the scope list. It is not automatically
injected.

Selecting a finder scope will reset any other querystring arguments. Selecting
the ``all`` finder scope will result in being redirected to a page without
querystring arguments.

Selecting a finder scope *will not* automatically apply the find to your
paginated result-set. This must be done manually.

Example: Applying Finder Scopes
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    This example assumes a simple blog application is being modified, with a
    ``posts`` database table containing the fields ``id``, ``active``,
    ``title``, ``body``, and ``created``.

Once a finder scope is selected, it must still be applied to the paginated
result-set. This can be done in the mapped action as follows:

.. code-block:: php

    public function index()
    {
        $this->Crud->action()->config('scaffold.index_finder_scopes', [
            [
                'title' => __('All'),
                'finder' => 'all',
            ],
            [
                'title' => __('Active'),
                'finder' => 'active',
            ],
            [
                'title' => __('Inactive'),
                'finder' => 'inactive',
            ],
        ]);

        // We don't need to check for `all` as it is the default findMethod
        if (in_array($this->request->query('finder'), ['active', 'inactive'])) {
            $this->Crud->action()->config('findMethod', $this->request->query('finder'));
        }
        return $this->Crud->execute();
    }

Now that the ``findMethod`` can be mapped, the respective custom find methods
must be created in the ``PostsTable`` class.

.. code-block:: php

    use Cake\ORM\Query;
    use Cake\ORM\Table;

    class PostsTable extends Table
    {
        public function findActive(Query $query, array $options)
        {
            $query->where([$this->aliasField('active') => true]);

            return $query;
        }

        public function findInactive(Query $query, array $options)
        {
            $query->where([$this->aliasField('active') => false]);

            return $query;
        }
    }

Custom Download Links
---------------------

Custom Blocks
-------------
