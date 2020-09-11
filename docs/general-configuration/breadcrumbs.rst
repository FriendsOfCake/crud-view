Breadcrumbs
===========

.. note::

    This functionality is only available for CakePHP 3.3.6 and up.

You can indicate the current page's location within a navigational hierarchy
called a breadcrumb. *CrudView* does not output breadcrumbs by default, but they
may be enabled via the ``scaffold.breadcrumbs`` configuration key.

.. warning::

    Setting this configuration key to anything other than an array will
    result in no breadcrumbs.

Adding Breadcrumbs
------------------

The ``scaffold.breadcrumbs`` configuration key takes an array of
``CrudView\Breadcrumb\Breadcrumb`` objects:

.. code-block:: php

    use CrudView\Breadcrumb\Breadcrumb;

    $this->Crud->action()->setConfig('scaffold.breadcrumbs', [
        new BreadCrumb('Home'),
    ]);

By default, ``CrudView\Breadcrumb\Breadcrumb`` objects will output plain-text
breadcrumb entries. However, they also take ``url`` and ``options`` arrays:

.. code-block:: php

    use CrudView\Breadcrumb\Breadcrumb;

    $this->Crud->action()->setConfig('scaffold.breadcrumbs', [
        new BreadCrumb('Home', ['controller' => 'Posts', 'action' => 'index'], ['class' => 'derp']),
    ]);

Active Breadcrumb
-----------------

You may also set any given breadcrumb to "active" by either setting the
``class`` option to 'active' or using a
``CrudView\Breadcrumb\ActiveBreadCrumb`` object:

.. code-block:: php

    // Using the "class" option method:
    use CrudView\Breadcrumb\Breadcrumb;

    $this->Crud->action()->setConfig('scaffold.breadcrumbs', [
        new BreadCrumb('Home', '#', ['class' => 'active']),
    ]);

    // Using the ActiveBreadCrumb method:
    use CrudView\Breadcrumb\ActiveBreadCrumb;

    $this->Crud->action()->setConfig('scaffold.breadcrumbs', [
        new ActiveBreadCrumb('Home', '#'),
    ]);

Custom Layouts with Breadcrumbs
-------------------------------

Breadcrumbs are output in the ``layout`` portion of template rendering,
outside of ``action`` template rendering. If you wish to change the layout but
reuse the breadcrumb template logic, use the ``CrudView.breadcrumbs`` element
like so:

.. code-block:: php

    <?= $this->element('breadcrumbs') ?>
