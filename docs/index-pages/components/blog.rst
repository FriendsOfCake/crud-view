Index as a Blog
===============

Render your index page as a set of posts.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.index_type', 'blog');

Customizing the Blog fields
---------------------------

The post has two main options:

- ``index_blog_title_field``: (default: ``title``) Controls the field used for the blog title.
- ``index_blog_body_field``: (default: ``body``) Controls the field used for the blog body.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.index_blog_title_field', 'name');
    $action->config('scaffold.index_blog_body_field', 'content');
