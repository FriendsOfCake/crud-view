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

Index Action Elements
---------------------

All the *CrudView* templates are built from several elements that can be
overridden by creating them in your own ``templates/element`` folder. The
following sections will list all the elements that can be overridden for each
type of action.

In general, if you want to override a template, it is a good idea to copy the
original implementation from
``vendor/friendsofcake/crud-view/templates/element``

search
  Create ``templates/element/search.ctp`` for having full control over how
  the search filters are displayed in your pagination table. You can expect the
  ``$searchInputs`` and ``$searchOptions`` variables to be available

index/table
  Create ``templates/element/index/table.ctp`` To implement your own
  table.

index/pagination
  Create ``templates/element/index/pagination.ctp`` To implement your own
  pagination links and counter.

index/bulk_actions/table
  Create ``templates/element/index/bulk_actions/table.ctp`` for changing how
  the bulk action inputs for the whole table. You can expect the
  ``$bulkActions``, ``$primaryKey`` and ``$singularVar`` variables to be
  available.

index/bulk_actions/record
  Create ``templates/element/index/bulk_actions/record.ctp`` for changing how
  the bulk action inputs for each row are displayed. You can expect the
  ``$bulkActions``, ``$primaryKey`` and ``$singularVar`` variables to be
  available.

index/bulk_actions/form_start
  Create ``templates/element/index/bulk_actions/form_start.ctp`` To customize
  the Form create call for bulk actions

index/bulk_actions/form_end
  Create ``templates/element/index/bulk_actions/form_end.ctp`` To customize
  the Form end call for bulk actions
