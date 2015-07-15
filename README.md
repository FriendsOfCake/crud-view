Crud View
=========

Automated admin backend based on your Crud configuration.

This project is in early stage of development, do not use it production unless you want to get down and dirty on the code :)

Documentation
=============
You can find the WIP detailed usage documentation [here](http://crud-view.readthedocs.org/en/latest/).

Quick Start
===========

1) Install the plugin using `composer require --prefer-dist friendsofcake/crud-view:dev-master`.

2) Add ``Plugin::load('Crud');``, ``Plugin::load('CrudView');`` &  ``Plugin::load('BootstrapUI');`` to your ``app/config/bootstrap.php``

3) Configure [Crud](http://crud.readthedocs.org/en/latest/quick-start.html) as per your needs.

4) Change ``AppController::$viewClass`` to ``CrudView\View\CrudView``

5) Load the ``CrudView.View``, ``Crud.RelatedModels`` and ``Crud.Redirect`` listeners.

6) Hopefully going to ``/<your controller with crud enabled/`` should just work.

Example controller
==================

```php
<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Crud\Controller;
use Crud\Controller\ControllerTrait;

class AppController extends Controller
{
    use ControllerTrait;

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        $this->viewClass = 'CrudView\View\CrudView';
        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.Index',
                'Crud.Add',
                'Crud.Edit',
                'Crud.View',
                'Crud.Delete',
            ],
            'listeners' => [
                'CrudView.View',
                'Crud.RelatedModels',
                'Crud.Redirect',
            ],
        ]);
    }
}
```
