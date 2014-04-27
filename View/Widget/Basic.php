<?php
namespace CrudView\View\Widget;

class Basic extends \Cake\View\Widget\Basic {

	public function render(array $data) {
		if ($data['type'] === 'hidden') {
			return parent::render($data);
		}

		if (in_array($data['type'], ['text', 'number'])) {
			$data['class'] .= ' form-control';
		}

		$return = parent::render($data);
		return $return;
	}

}
