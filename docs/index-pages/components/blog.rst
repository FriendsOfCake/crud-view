Index as a Blog
===============

Render your index page as a set of posts.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.index_type', 'blog');

Customizing the Blog fields
---------------------------

The blog index type has two main options:

- ``scaffold.index_title_field``: (default: ``title``) Controls the field used for the blog title.
- ``scaffold.index_body_field``: (default: ``body``) Controls the field used for the blog body.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.index_title_field', 'name');
    $action->config('scaffold.index_body_field', 'content');

    // the following are deprecated ways of setting the title and body
    $action->config('scaffold.index_blog_title_field', 'name');
    $action->config('scaffold.index_blog_body_field', 'content');
