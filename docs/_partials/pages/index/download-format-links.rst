Download Format Links
---------------------

The ``scaffold.index_formats`` configuration key can be used to customize
"Download Links". These are alternative methods of displaying the current index
page, and can be used to expose the paginated data in JSON, XML, or other
formats. The output of each format can be customized to your specifications.

The ``scaffold.index_formats`` option takes an array of data, where the "key" is
the title to use for the link, and the value is the url.

.. code-block:: php

    use Cake\Routing\Router;

    // link to the current page, except with extensions `json` or `xml`
    // include the querystring argument as specified or you will lose any
    // currently applied filters
    $action = $this->Crud->action();
    $action->config('scaffold.index_formats', [
        'JSON' => Router::url(['_ext' => 'json', '?' => $this->request->query]),
        'XML' => Router::url(['_ext' => 'xml', '?' => $this->request->query]),
    ]);

Download links are displayed near the bottom-left of the index page and will
open in a new window.

Example: CSV Download Link
~~~~~~~~~~~~~~~~~~~~~~~~~~

.. note::

    This example assumes a simple blog application is being modified, with a
    ``posts`` database table containing the fields ``id``, ``active``,
    ``title``, ``body``, and ``created``.

To implement a simple csv download link, the ``friendsofcake/cakephp-csvview``
plugin should be installed. This plugin will handle the actual rendering of
csv files at the CakePHP view layer.

.. code-block:: bash

  composer require friendsofcake/cakephp-csvview:~3.0

Next, the ``csv`` extension must be connected so that it can be properly parsed.
This can be done by modifying the ``config/routes.php`` file. Below is a
semi-complete example:

.. code-block:: php

    Router::scope('/', function (RouteBuilder $routes) {
        $routes->extensions(['csv']);
        // other routes go here
    });

To complete the initial setup, the RequestHandler should be notified to use the
``CsvView.View`` class whenever an extension of ``csv`` is detected. The
following can be added to the ``AppController::initialize()`` to do
application-wide:

.. code-block:: php

    $this->loadComponent('RequestHandler', [
        'viewClassMap' => ['csv' => 'CsvView.Csv']
    ]);

Once the initial setup of the CsvView plugin is complete, the ``index()`` action
can be modified to add a CSV Download Link.

.. code-block:: php

    public function index()
    {
        // only show the id, title, and created fields for csv output
        if ($this->request->getParam('_ext') === 'csv') {
            $this->set('_serialize', ['posts']);
            $this->set('_extract', ['id', 'active', 'title', 'created']);
        }

        $this->Crud->action()->config('scaffold.index_formats', [
            'CSV' => Router::url(['_ext' => 'csv', '?' => $this->request->query]),
        ]);
        return $this->Crud->execute();
    }
