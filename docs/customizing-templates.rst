Customizing Templates
=====================

Despite *CrudView* being quite smart at guessing how to display your data and
having great defaults, it is very often that you need to customize the look and
feel of your Admin application.


.. include:: /_partials/fields/index.rst
.. include:: /_partials/fields/field-settings.rst
.. include:: /_partials/fields/field-blacklist.rst
.. include:: /_partials/fields/formatter-callable.rst
.. include:: /_partials/fields/formatter-element.rst

Changing Field Header or Label Names
------------------------------------

*CrudView* infers the name of the field by splitting the field so that it can
be read by a human. Sometimes this is just not enough, or you may wish to show
an entirely different header in a table or label in a form.

Changing Form Input Labels
~~~~~~~~~~~~~~~~~~~~~~~~~~

In our ``add()`` and ``edit()`` actions, you can specify the input label for
title for any of the fields by using the ``scaffold.fields`` configuration

.. code-block:: php

    $action = $this->Crud->action();
    $action->setConfig('scaffold.fields', [
        'author_id' => ['label' => 'Author Name'],
        // The rest of the fields to display here
    ]);

Adding Controller Actions to utilize Crud Actions
-------------------------------------------------

It's easy to add an action to a controller that makes use of another
*CrudView* action.

This does use the template provided by the edit action:

.. code-block:: php

    public function account() {
        $this->Crud->mapAction('account', [
            'className' => 'Crud.Edit',
            'view' => 'edit',
        ]);
        return $this->Crud->execute(null, $this->Auth->user('id'));
    }

By default, it can be overwritten by providing a custom ``register.ctp``:

.. code-block:: php

    public function register() {
        $this->Crud->mapAction('register', [
            'className' => 'Crud.Add',
        ]);
        return $this->Crud->execute();
    }

Overriding Template Elements
----------------------------

All the *CrudView* templates are built from several elements that can be
overridden by creating them in your own ``templates/element`` folder. The
following sections will list all the elements that can be overridden for each
type of action.

In general, if you want to override a template, it is a good idea to copy the
original implementation from
``vendor/friendsofcake/crud-view/templates/element``
