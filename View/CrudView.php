<?php
namespace CrudView\View;

use \Cake\View\Error\MissingViewException;

class CrudView extends \Cake\View\View {

	protected function _getViewFileName($name = null) {
		try {
			return parent::_getViewFileName($name);
		} catch (MissingViewException $exception) {
			return parent::_getViewFileName('/Scaffolds/' . $this->view);
		}
	}

}
