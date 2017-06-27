Index as a Gallery
==================

Render your index page as a gallery.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.index_type', 'gallery');

Customizing the Gallery fields
------------------------------

The gallery index type has several options:

- ``scaffold.index_title_field``: (default: ``displayField`` for current table)
  Controls the field used for each gallery entry title.
- ``scaffold.index_image_field``: (default: ``image``) Controls the field used
  for each gallery entry image.
- ``scaffold.index_body_field``: (default: ``body``) Controls the field used for
  each gallery entry body.
- ``scaffold.index_gallery_css_classes``: (default: ``col-sm-6 col-md-3``)
  Controls the css classes applied to each gallery entry, useful for specifying
  how many entries should go on a single page.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.index_title_field', 'name');
    $action->config('scaffold.index_image_field', 'image');
    $action->config('scaffold.index_body_field', 'content');
    $action->config('scaffold.index_gallery_css_classes', 'col-sm-4 col-md-2');

Customizing Gallery Field Output
--------------------------------

For each field, we will also retrieve configuration from the ``scaffold.fields``
configuration key for formatting each field:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.fields', [
        'image' => [
            'width' => '240',
            'height' => '240'
        ],
    ]);

Default Image
-------------

If no image is retrieved, *CrudView* will default to the following transparent
gif:

.. code-block:: html

    data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==

Available Variables
-------------------

The following variables are available for use within the element:

- `indexImageField`: The field containing the gallery entry image
- `indexTitleField`: The field containing the gallery entry title
- `indexBodyField`: The field containing the gallery entry body
- `fields`: List of fields to show and their options
- `actions`: A list of actions that can be displayed for the index page.
- `bulkActions`: A list of bulk actions associated with this resource
- `primaryKey`: The name of the record's primary key field.
- `singularVar`: The singular version of the resource name.
- `viewVar`: Reference to the name of the variable holding all records.
- plural of `viewVar`: The set of records.
