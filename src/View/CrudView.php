<?php
namespace CrudView\View;

use Cake\View\Exception\MissingTemplateException;
use Cake\View\View;

class CrudView extends View
{

    /**
     * Returns filename of given action's template file (.ctp) as a string.
     *
     * @param string|null $name Controller action to find template filename for.
     * @return string Template filename
     * @throws \Cake\View\Exception\MissingTemplateException When a view file could not be found.
     */
    protected function _getViewFileName($name = null)
    {
        try {
            return parent::_getViewFileName($name);
        } catch (MissingTemplateException $exception) {
            return parent::_getViewFileName('/Scaffolds/' . $this->view);
        }
    }
}
