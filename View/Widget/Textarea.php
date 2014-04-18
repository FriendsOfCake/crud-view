<?php
namespace CrudView\View\Widget;

class Textarea extends \Cake\View\Widget\Textarea {

	public function render(array $data) {
		$data['class'] = 'form-control';
		$data['rows'] = 6;

		return parent::render($data);
		return $return;
	}

}
