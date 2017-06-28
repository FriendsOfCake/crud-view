Index Finder Scopes
-------------------

In some cases, it is helpful to show quick links to pre-filtered datasets.
Rather than force users to select all the filters, *CrudView* enables the ability
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

.. note::

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
        if (in_array($this->request->getQuery('finder'), ['active', 'inactive'])) {
            $this->Crud->action()->config('findMethod', $this->request->getQuery('finder'));
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
