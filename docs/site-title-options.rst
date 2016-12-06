Site Title Options
==================

Every page has what's called the site title on the left side of the menu bar. If you want, you can customize it.

Site Title
----------

You can use the ``scaffold.site_title`` config variable to modify the title. If not set, it will fallback to the following deprecated alternatives:

- ``Configure::read('CrudView.siteTitle')``
- ``$action->config('scaffold.brand')``: Deprecated
- ``Configure::read('CrudView.brand')``: Deprecated

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.site_title', 'My Admin Site');

Site Title Link
---------------

You can use the ``scaffold.site_title_link`` config variable to modify the title link. If not set, the title will not be made into a link. Both urls and cakephp route arrays are supported.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.site_title_link', '/');

Site Title Image
----------------
You can use the ``scaffold.site_title_image`` config variable to modify the title link. If set, it replaces ``scaffold.site_title``.

.. code-block:: php

    $action = $this->Crud->action();
    // Use an image included in your codebase
    $action->config('scaffold.site_title_image', 'site_image.png');

    // Use an exact url
    $action->config('scaffold.site_title_image', 'http://www.google.com/images/logos/google_logo_41.png');
