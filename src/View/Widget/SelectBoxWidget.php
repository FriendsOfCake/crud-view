<?php
namespace CrudView\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\SelectBoxWidget as CoreSelectBoxWidget;

class SelectBoxWidget extends CoreSelectBoxWidget
{

    public function render(array $data, ContextInterface $context)
    {
        $data['class'] = 'form-control';
        return parent::render($data, $context);
    }
}
