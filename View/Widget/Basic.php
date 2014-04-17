<?php
namespace CrudView\View\Widget;

class Basic extends \Cake\View\Widget\Basic {

	public function render(array $data) {
		if (in_array($data['type'], ['text', 'number'])) {
			// $data['options']['class'] = 'form-controller';
			$data['class'] = 'form-control';
		}
		// debug($data);

		return parent::render($data);
	}

}
