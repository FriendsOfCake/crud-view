Index as a Blog
===============

Render your index page as a set of posts.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.index_type', 'blog');

Customizing the Blog fields
---------------------------

The blog index type has two main options:

- ``scaffold.index_title_field``: (default: ``displayField`` for current table) Controls the field used for the blog title.
- ``scaffold.index_body_field``: (default: ``body``) Controls the field used for the blog body.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.index_title_field', 'name');
    $action->config('scaffold.index_body_field', 'content');

Available Variables
-------------------

The following variables are available for use within the element:

- `indexTitleField`: The field containing the post title
- `indexBodyField`: The field containing the post body
- `fields`: List of fields to show and their options
- `actions`: A list of actions that can be displayed for the index page.
- `bulkActions`: A list of bulk actions associated with this resource
- `primaryKey`: The name of the record's primary key field.
- `singularVar`: The singular version of the resource name.
- `viewVar`: Reference to the name of the variable holding all records.
- plural of `viewVar`: The set of records.
