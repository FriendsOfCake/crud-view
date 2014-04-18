<?php
namespace CrudView\View\Widget;

class SelectBox extends \Cake\View\Widget\SelectBox {

	public function render(array $data) {
		$data['class'] = 'form-control';
		return parent::render($data);
	}

}
