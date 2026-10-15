Index as a Table
================

By default, the index page is a table with each of the models content columns
and links to show, edit and delete the object. There are many ways to
customize what gets displayed.

Defining Columns
----------------

By default, all fields are displayed on the ``index`` page. To display an
attribute or a method on a record, you can use the ``scaffold.fields``
config option.

.. code-block:: php

    $action = $this->Crud->action();
    $action->setConfig('scaffold.fields', ['id', 'title']);

To specify the title used in the pagination header, you need to set
``scaffold.fields`` to an associative array and use the ``title`` parameter:

.. code-block:: php

    $action = $this->Crud->action();
    $action->setConfig('scaffold.fields', [
      'author_id' => ['title' => 'Author Name'],
    ]);

.. include:: /_partials/pages/index/elements.rst
