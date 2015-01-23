<?php
namespace CrudView\View\Widget;

class SelectBoxWidget extends \Cake\View\Widget\SelectBoxWidget {

	public function render(array $data, \Cake\View\Form\ContextInterface $context) {
		$data['class'] = 'form-control';
		return parent::render($data, $context);
	}

}
