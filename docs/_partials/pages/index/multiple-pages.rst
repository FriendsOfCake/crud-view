Multiple Index Pages
--------------------

Sometime you may want more than one index page for a resource to represent
different views to the user. The template rendered by a mapped Crud action can
be set with the ``view`` setting, so an extra index page can be created by
mapping another action which uses the ``index`` template:

.. code-block:: php

    public function featured()
    {
        $this->Crud->mapAction('featured', [
            'className' => 'Crud.Index',
            'view' => 'index',
        ]);

        return $this->Crud->execute();
    }

The same can be achieved by setting the view on the action object:

.. code-block:: php

    $action = $this->Crud->action('featured');
    $action->view('index');
