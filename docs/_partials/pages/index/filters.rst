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
                'enable' => true,

                // Whether to use auto complete for select fields. Default `true`.
                'autocomplete' => true,

                // Whether to use selectize for select fields. Default `true`.
                'selectize' => true,

                // The search behavior collection to use. Default "default".
                'collection' => 'default',

                // Fields config for generation filter inputs. If `null` the field
                // inputs will be derived based on filter collection. Default `null``.
                'fields' => null
            ]);

            $this->Crud->execute();
        }
    }
