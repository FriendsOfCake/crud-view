<?php
namespace CrudView\View\Widget;

class Label extends \Cake\View\Widget\Label {

	public function render(array $data) {
		$data['class'] = 'col-sm-2 control-label';

		return parent::render($data);
	}

}
