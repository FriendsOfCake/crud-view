<?php
namespace CrudView\View;

use Cake\Core\Configure;
use Cake\View\Exception\MissingTemplateException;
use Cake\View\View;

class CrudView extends View
{
    /**
     * The name of the layout file to render the view inside of. The name specified
     * is the filename of the layout in /app/Template/Layout without the .ctp
     * extension.
     *
     * @var string
     */
    public $layout = 'CrudView.default';

    /**
     * Initialization hook method.
     *
     * Properties like $helpers etc. cannot be initialized statically in your custom
     * view class as they are overwritten by values from controller in constructor.
     * So this method allows you to manipulate them as required after view instance
     * is constructed.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->_setupBootstrapUI();
        $this->_setupPaths();
        $this->_setupViewblocks();
    }

    /**
     * Initializes the bootstrap-ui plugin
     *
     * @return void
     */
    protected function _setupBootstrapUI()
    {
        $this->loadHelper('Html', ['className' => 'BootstrapUI.Html']);
        $this->loadHelper('Form', ['className' => 'BootstrapUI.Form']);
        $this->loadHelper('Flash', ['className' => 'BootstrapUI.Flash']);
        $this->loadHelper('Paginator', ['className' => 'BootstrapUI.Paginator']);
    }

    /**
     * Initializes the crud-view template paths
     *
     * @return void
     */
    protected function _setupPaths()
    {
        $crudTemplates = dirname(dirname(__FILE__)) . DS . 'Template' . DS;
        $paths = (array)Configure::read('App.paths.templates');

        if (!in_array($crudTemplates, $paths)) {
            $paths[] = $crudTemplates;
            Configure::write('App.paths.templates', $paths);
        }
    }

    /**
     * Initializes viewblocks for use as panels
     *
     * @return void
     */
    protected function _setupViewblocks()
    {
        $viewblocks = $this->get('viewblocks', []);
        foreach ($viewblocks as $viewblock => $set) {
            $output = '';
            foreach ($set as $key => $type) {
                if ($type == 'element') {
                    $output .= $this->element($key);
                } elseif ($type == 'Html::css') {
                    $output .= $this->Html->css($key);
                } elseif ($type == 'Html::script') {
                    $output .= $this->Html->script($key);
                } else {
                    $output .= $key;
                }
            }
            $this->Blocks->set($viewblock, $output);
        }
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
            $this->subDir = null;
            $this->viewPath = 'Scaffolds';
            return parent::_getViewFileName($this->view);
        }
    }
}
