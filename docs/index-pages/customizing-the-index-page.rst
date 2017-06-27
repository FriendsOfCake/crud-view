Customizing the Index Page
==========================

Customizing Fields
------------------

Fields may be specified via the ``scaffold.fields`` configuration key. By
default, this will contain a list of all columns associated with the Table being
in scope. To limit the fields used, simply specify an array of fields:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.fields', ['title', 'description']);

.. include:: /_partials/fields/field-settings.rst
.. include:: /_partials/fields/field-blacklist.rst
.. include:: /_partials/fields/formatter-callable.rst
.. include:: /_partials/fields/formatter-element.rst

.. include:: /_partials/pages/index/buttons.rst
.. include:: /_partials/pages/index/finder-scopes.rst
.. include:: /_partials/pages/index/filters.rst
.. include:: /_partials/pages/index/multiple-pages.rst

Custom Download Links
---------------------

.. include:: /_partials/pages/form/viewblocks.rst
.. include:: /_partials/pages/form/elements.rst
