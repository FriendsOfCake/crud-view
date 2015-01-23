crud-view
=========

(Incubator project) Automated admin backend based on your Crud configuration

This project is in very very early stage of development, do not use it production unless you want to get down and dirty on the code :)

Current Twitter Bootstrap 3 template: [sb-admin](http://startbootstrap.com/templates/sb-admin) from [startbootstrap.com](http://startbootstrap.com)

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

class AppController extends \Cake\Controller\Controller {

	use \Crud\Controller\ControllerTrait;

	public $viewClass = 'CrudView\View\CrudView';

	public $components = [
		'Crud.Crud' => [
			'actions' => ['Crud.Index', 'Crud.Add', 'Crud.Edit', 'Crud.View', 'Crud.Delete'],
			'listeners' => ['CrudView.View', 'Crud.RelatedModels', 'Crud.Redirect']
		]
	];

	public $helpers = [
		'Form' => [
			'templates' => 'CrudView.forms',
			'widgets' => [
				'_default' => ['CrudView\View\Widget\BasicWidget'],
				'textarea' => ['CrudView\View\Widget\TextareaWidget'],
				'select' => ['CrudView\View\Widget\SelectBoxWidget'],
				'label' => ['CrudView\View\Widget\LabelWidget'],
				'datetime' => ['CrudView\View\Widget\DateTimeWidget', 'select']
			]
		]
	];

}
```
