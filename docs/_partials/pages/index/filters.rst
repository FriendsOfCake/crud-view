Index Filters
-------------

The ``ViewSearch`` listener generates filter inputs for filtering records on your
index action. It requries `friendsofcake/search <https://packagist.org/packages/friendsofcake/search>`
to be installed and filters configured for your model using the search manager.

.. code-block:: php

    <?php
    class SamplesController extends AppController {

        public function index() {
            $this->Crud->addListener('Crud.Crud');
            $this->Crud->addListener('Crud.ViewSearch', [
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
                    'filter_1 => [
                        // Any option which you can use Form::control() options.
                    ],
                    // Control options for other filters.
                ]
            ]);

            $this->Crud->execute();
        }
    }

Here's an e.g. of how configure filter controls options through search manager itself:

.. code-block:: php

    <?php
    // Samples::initialize()
    $this->searchManager()
        ->useCollection('backend')
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
