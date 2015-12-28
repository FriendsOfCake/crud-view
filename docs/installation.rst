Installation
============

Installing CRUD view requires only a few steps

Requirements
------------

* CakePHP 3.x

Getting the Code
----------------

The recommended installation method for this plugin is by using composer.

In your aplication forlder execute:

.. code-block:: sh

  composer require friendsofcake/crud-view

It is highly recommended that you install the ``Search`` plugin as well:

.. code-block:: sh

    composer require friendsofcake/search

Loading the plugin
------------------

Execute the following lines from your application folder:

.. code-block:: sh

    bin/cake plugin load Crud
    bin/cake plugin load CrudView
    bin/cake plugin load BootstrapUI
    bin/cake plugin load Search
