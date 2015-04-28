<?php
namespace CrudView\View;

use Cake\View\Exception\MissingTemplateException;
use Cake\View\View;

class CrudView extends View
{
    public $layout = 'CrudView.default';

    public function initialize(array $config = [])
    {
        parent::initialize($config);
        $this->loadHelper('Html', ['className' => 'BootstrapUI.Html']);
        $this->loadHelper('Form', ['className' => 'BootstrapUI.Form']);
        $this->loadHelper('Flash', ['className' => 'BootstrapUI.Flash']);
        $this->loadHelper('Paginator', ['className' => 'BootstrapUI.Paginator']);
    }

    /**
     * Finds an element filename, returns false on failure.
     *
     * @param string $name The name of the element to find.
     * @return mixed Either a string to the element filename or false when one can't be found.
     */
    protected function _getElementFileName($name)
    {
        $filename = parent::_getElementFileName($name);
        if ($filename) {
            return $filename;
        }

        return parent::_getElementFileName('CrudView.' . $name);
    }

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
            try {
                return parent::_getViewFileName('Scaffolds/' . $this->view);
            } catch (MissingTemplateException $exception) {
                return parent::_getViewFileName('CrudView.Scaffolds/' . $this->view);
            }
        }
    }
}
