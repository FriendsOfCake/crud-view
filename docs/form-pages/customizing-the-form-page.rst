Customizing the Form Page
=========================

Customizing Form Fields
-----------------------

Form fields may be specified via the ``scaffold.fields`` configuration key.
By default, this will contain a list of all columns associated with the primary
entity being edited. The value of this will be sent to ``FormHelper::inputs()``.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.fields', ['title', 'description']);

You may also use the ``scaffold.fields_blacklist`` configuration key to remove
fields from the output if you are using the default automatic field population
functionality:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.fields_blacklist', ['created', 'modified']);

Finally, if you wish to use the default automatic field population functionality
but want to specify settings for a few of the fields, you can use the
``scaffold.field_settings`` configuration key:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.field_settings', [
        'title' => [
          'placeholder' => 'the title of the blog post'
        ]
    ]);

Form Submission
---------------

Form Submission Redirect
~~~~~~~~~~~~~~~~~~~~~~~~

By default, the Crud plugin will redirect all form submissions to the
controller's ``index`` action. This can be changed by setting the
``_redirect_url`` view variable:

.. code-block:: php

    $this->set('_redirect_url', ['action' => 'home']);

Form Submission Check
~~~~~~~~~~~~~~~~~~~~~

By default, closing the a form page in your browser will result in lost data.
However, you may force a user prompt by enabling dirty form checks using the
``scaffold.form_enable_dirty_check`` configuration key:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.form_enable_dirty_check', true);

Submit Buttons
--------------

Changing the Submit Button Text
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can change the submit button text from it's default using the
``scaffold.form_submit_button_text`` configuration key.

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.form_submit_button_text', _('Submit'));

Modifying the Default Extra Buttons
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

By default, we should the following extra buttons for forms:

- Save & continue editing: Results in a form submission
- Save & create new: Results in a form submission
- Back: A link to the index page

To use the defaults, you may either omit the configuration key **or** set it
to true:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.form_submit_extra_buttons', true);

You can also customize this by using the ``scaffold.form_submit_extra_buttons``
configuration key as follows:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.form_submit_extra_buttons', [
        [
            'title' => __d('crud', 'Save & continue editing'),
            'options' => ['class' => 'btn btn-success btn-save-continue', 'name' => '_edit', 'value' => true],
            'type' => 'button',
        ],
        [
            'title' => __d('crud', 'Save & create new'),
            'options' => ['class' => 'btn btn-success', 'name' => '_add', 'value' => true],
            'type' => 'button',
        ],
        [
            'title' => __d('crud', 'Back'),
            'url' => ['action' => 'index'],
            'options' => ['class' => 'btn btn-default', 'role' => 'button', 'value' => true],
            'type' => 'link',
        ],
    ]);

Specified values will override the defaults, and will be output in the order
specified.

Disabling the Default Extra Buttons
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Rather than modifying the default extra buttons, you can also disable them
completely:

.. code-block:: php

    $action = $this->Crud->action();
    $action->config('scaffold.form_submit_extra_buttons', false);

Custom Blocks
-------------

The following custom view blocks are available for use within forms:

- ``form.sidebar``: Rendered on the side of a form. Will also change the form
  width
- ``form.before``: Rendered before a form.
- ``form.after``: Rendered after a form.

Form Action Elements
---------------------

All the ``CrudView`` templates are built from several elements that can be
overridden by creating them in your own ``src/Template/Element`` folder. The
following sections will list all the elements that can be overridden for each
type of action.

In general, if you want to override a template, it is a good idea to copy the
original implementation from
``vendor/friendsofcake/crud-view/src/Template/Element``

action-header
  Create ``src/Template/Element/action-header.ctp`` to have full control over
  what is displayed at the top of the page. This is shared across all page
  types.

form/buttons
  Create ``src/Template/Element/form/buttons.ctp`` to change what is displayed
  for form submission. You can expect the ``$formSubmitButtonText`` and
  ``$formSubmitExtraButtons`` variables to be available
