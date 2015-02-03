<?php
namespace CrudView\View;

use \Cake\View\Exception\MissingTemplateException;

class CrudView extends \Cake\View\View {

	protected function _getViewFileName($name = null) {
		try {
			return parent::_getViewFileName($name);
		} catch (MissingTemplateException $exception) {
			return parent::_getViewFileName('/Scaffolds/' . $this->view);
		}
	}

}
