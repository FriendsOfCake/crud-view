Multiple Index Pages
--------------------

Sometime you may want more than one index page for a resource to represent
different views to the user. If multiple index pages exist, *CrudView* will
automatically build links at the top of the ``index`` page. Including multiple
views is simple and requires setting the ``index`` view in your action.

.. code-block:: php

    $action = $this->Crud->action();
    $action->view('index');
