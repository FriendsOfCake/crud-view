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
    $action->setConfig('scaffold.form_enable_dirty_check', true);
