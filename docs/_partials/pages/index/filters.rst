Index Filters
-------------

The ``ViewSearch`` listener generates filter inputs for filtering records on your
index action. It requries `friendsofcake/search <https://packagist.org/packages/friendsofcake/search>`
to be installed and filters configured for your model using the search manager.

.. code-block:: php

    <?php
    declare(strict_types=1);

    namespace App\Controller;
    use App\Controller\AppController;

    class SamplesController extends AppController
    {
        public function initialize(): void
        {
            parent::initialize();
            // Enable PrgComponent so search form submissions
            // properly populate querystring parameters for the SearchListener
            $this->loadComponent('Search.Prg', [
                'actions' => [
                    'index',
                ],
            ]);
        }

        public function index()
        {
            // Enable the SearchListener
            $this->Crud->addListener('search', 'Crud.Search', [
                // The search behavior collection to use. Default "default".
                'collection' => 'admin',
            ]);

            // Enable the ViewSearch listener
            $this->Crud->addListener('viewSearch', 'CrudView.ViewSearch', [
                // Indicates whether is listener is enabled.
                'enabled' => true,

                // Whether to use auto complete for select fields. Default `true`.
                // This requires you have `Crud.Lookup` action enabled for that
                // related model's controller.
                // http://crud.readthedocs.io/en/latest/actions/lookup.html
                'autocomplete' => true,

                // Whether to use selectize for select fields. Default `true`.
                'selectize' => true,

                // The search behavior collection to use. Default "default".
                'collection' => 'default',

                // Config for generating filter controls. If `null` the
                // filter controls will be derived based on filter collection.
                // You can use "form" key in filter config to specify control options.
                // Default `null`.
                'fields' => [
                    // Key should be the filter name.
                    'filter_1' => [
                        // Any option which you can use Form::control() options.
                    ],
                    // Control options for other filters.
                ]
            ]);

            return $this->Crud->execute();
        }
    }

Here's an e.g. of how configure filter controls options through search manager itself:

.. code-block:: php

    <?php
    declare(strict_types=1);

    namespace App\Model\Table;
    use Cake\ORM\Table;

    class SamplesTable extends Table
    {
        public function initialize(array $config): void
        {
            parent::initialize($config);

            $this->addBehavior('Search.Search');
            $this->searchManager()
                ->useCollection('default')
                ->add('q', 'Search.Like', [
                    'field' => ['title', 'body'],
                    'form' => [
                        'data-foo' => 'bar'
                    ]
                ])
                ->add('category_id', 'Search.Value', [
                    'form' => [
                        'type' => 'select',
                        'class' => 'no-selectize'
                    ]
                ]);
        }
    }
