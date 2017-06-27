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
