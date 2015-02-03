<?php
namespace CrudView\View\Widget;

use \Cake\View\Form\ContextInterface;
use \Cake\View\Widget\TextareaWidget as CoreTextareaWidget;

class TextareaWidget extends CoreTextareaWidget
{

    public function render(array $data, ContextInterface $context)
    {
        $data['class'] = 'form-control';
        $data['rows'] = 6;

        return parent::render($data, $context);
    }
}
