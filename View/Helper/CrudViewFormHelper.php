<?php

App::uses('BoostCakeFormHelper', 'BoostCake.View/Helper');

class CrudViewFormHelper extends BoostCakeFormHelper {

	public function create($model = null, $options = array()) {
		$options += [
		  	'inputDefaults' => [
		  		'div' => 'form-group',
		  		'label' => [
		  			'class' => 'col col-md-3 control-label'
		  		],
		  		'wrapInput' => 'col col-md-9',
		  		'class' => 'form-control'
		  	],
		  	'class' => 'well form-horizontal'
		];

		return parent::create($model, $options);
	}

	public function input($fieldName, $options = array()) {
		$this->setEntity($fieldName);

		$options = $this->_parseOptions($options);

		if ($options['type'] === 'checkbox') {
			$options = Hash::merge($options, [
				'before' => '%label%',
				'class' => false
			]);
		}

		$html = parent::input($fieldName, $options);

		// Rewrite label position
		if ($options['type'] === 'checkbox') {
			preg_match('#(<label.*?>)<input.*>(.*?)(</label>)#sim', $html, $match);
			$html = str_replace($match[1], '', $html);
			$html = str_replace($match[2], '', $html);
			$html = str_replace($match[3], '', $html);
			$html = str_replace('%label%', '<label class="col col-md-3 control-label">' . $match[2] . '</label>', $html);
		}

		return $html;
	}

}
