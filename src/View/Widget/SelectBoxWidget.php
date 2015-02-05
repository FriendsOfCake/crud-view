<?php
namespace CrudView\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\SelectBoxWidget as CoreSelectBoxWidget;

class SelectBoxWidget extends CoreSelectBoxWidget
{

    /**
     * Render a select box form input.
     *
     * @param array $data Data to render with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string A generated select box.
     * @throws \RuntimeException When the name attribute is empty.
     */
    public function render(array $data, ContextInterface $context)
    {
        $data['class'] = 'form-control';
        return parent::render($data, $context);
    }
}
