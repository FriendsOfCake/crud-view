<?php
namespace CrudView\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\BasicWidget as CoreBasicWidget;

class BasicWidget extends CoreBasicWidget
{

    /**
     * Render a text widget or other simple widget like email/tel/number.
     *
     * @param array $data The data to build an input with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    public function render(array $data, ContextInterface $context)
    {
        if ($data['type'] === 'hidden') {
            return parent::render($data, $context);
        }

        if (empty($data['class'])) {
            $data['class'] = '';
        }

        if (in_array($data['type'], ['text', 'number'])) {
            $data['class'] .= ' form-control';
        }

        return parent::render($data, $context);
    }
}
