<?php
namespace CrudView\View\Widget;

use \Cake\View\Form\ContextInterface;
use \Cake\View\Widget\SelectBoxWidget;

class SelectBoxWidget extends SelectBoxWidget
{

    public function render(array $data, ContextInterface $context)
    {
        $data['class'] = 'form-control';
        return parent::render($data, $context);
    }
}
