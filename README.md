crud-view
=========

(Incubator project) Automated admin backend based on your Crud configuration

This project is in very very early stage of development, do not use it production unless you want to get down and dirty on the code :)

Usage
=====

1) make sure to follow the normal CRUD install settings

2) change ``AppController::$viewClass`` to ``CrudView\View\CrudView``

3) load the ``CrudView.View``, ``Crud.RelatedModels`` and ``Crud.Redirect`` listeners

4) configure the ``FormHelper`` to look like below

5) hopefully going to ``/<your controller with crud enabled/`` should just work

Example controller
==================

```php
<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Crud\Controller;

class AppController extends Controller
{
    use ControllerTrait;

    public function initialize()
    {
        parent::initialize();
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
