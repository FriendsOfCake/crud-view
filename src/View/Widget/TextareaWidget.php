<?php
namespace CrudView\View\Widget;

use \Cake\View\Form\ContextInterface;
use \Cake\View\Widget\TextareaWidget as CoreTextareaWidget;

class TextareaWidget extends CoreTextareaWidget
{

    /**
     * Render a text area form widget.
     *
     * @param array $data The data to build a textarea with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string HTML elements.
     */
    public function render(array $data, ContextInterface $context)
    {
        $data['class'] = 'form-control';
        $data['rows'] = 6;

        return parent::render($data, $context);
    }
}
