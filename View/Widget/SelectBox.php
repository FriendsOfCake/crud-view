<?php
namespace CrudView\View\Widget;

class SelectBox extends \Cake\View\Widget\SelectBox {

	public function render(array $data, \Cake\View\Form\ContextInterface $context) {
		$data['class'] = 'form-control';
		return parent::render($data, $context);
	}

}
