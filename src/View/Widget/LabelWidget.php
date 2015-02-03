<?php
namespace CrudView\View\Widget;

use \Cake\View\Form\ContextInterface;
use \Cake\View\Widget\LabelWidget;

class LabelWidget extends LabelWidget
{

    public function render(array $data, ContextInterface $context)
    {
        $data['class'] = 'col-sm-2 control-label';

        return parent::render($data, $context);
    }
}
