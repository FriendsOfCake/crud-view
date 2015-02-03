<?php
namespace CrudView\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\LabelWidget as CoreLabelWidget;

class LabelWidget extends CoreLabelWidget
{

    public function render(array $data, ContextInterface $context)
    {
        $data['class'] = 'col-sm-2 control-label';

        return parent::render($data, $context);
    }
}
