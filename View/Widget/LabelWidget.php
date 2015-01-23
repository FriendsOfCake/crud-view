<?php
namespace CrudView\View\Widget;

class LabelWidget extends \Cake\View\Widget\LabelWidget {

	public function render(array $data, \Cake\View\Form\ContextInterface $context) {
		$data['class'] = 'col-sm-2 control-label';

		return parent::render($data, $context);
	}

}
