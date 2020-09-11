Available Elements
------------------

All the *CrudView* templates are built from several elements that can be
overridden by creating them in your own ``templates/element`` folder. The
following sections will list all the elements that can be overridden for each
type of action.

In general, if you want to override a template, it is a good idea to copy the
original implementation from
``vendor/friendsofcake/crud-view/templates/element``

action-header
  Create ``templates/element/action-header.ctp`` to have full control over
  what is displayed at the top of the page. This is shared across all page
  types.

form/buttons
  Create ``templates/element/form/buttons.ctp`` to change what is displayed
  for form submission. You can expect the ``$formSubmitButtonText`` and
  ``$formSubmitExtraButtons`` variables to be available
