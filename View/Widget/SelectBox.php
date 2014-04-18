<?php
namespace CrudView\View\Widget;

class SelectBox extends \Cake\View\Widget\SelectBox {

	public function render(array $data) {
		$data['class'] = 'form-control';
		return '<div class="col-sm-10"> ' . parent::render($data) . '</div>';
	}

}
