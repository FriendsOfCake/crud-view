<?php
namespace CrudView\View\Widget;

class Label extends \Cake\View\Widget\Label {

	public function render(array $data, \Cake\View\Form\ContextInterface $context) {
		$data['class'] = 'col-sm-2 control-label';

		return parent::render($data, $context);
	}

}
