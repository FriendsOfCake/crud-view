<?php
namespace CrudView\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\LabelWidget as CoreLabelWidget;

class LabelWidget extends CoreLabelWidget
{

    /**
     * Render a label widget.
     *
     * @param array $data Data array.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    public function render(array $data, ContextInterface $context)
    {
        $data['class'] = 'col-sm-2 control-label';

        return parent::render($data, $context);
    }
}
