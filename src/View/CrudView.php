<?php
namespace CrudView\View;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\View\Exception\MissingTemplateException;
use Cake\View\View;
use CrudView\Traits\CrudViewConfigTrait;

/**
 * @property \AssetCompress\View\Helper\AssetCompressHelper $AssetCompress
 */
class CrudView extends View
{
    use CrudViewConfigTrait;

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

        $this->ensureConfig();
        $this->_setupPaths();
        $this->_setupHelpers();
        $this->_loadAssets();
    }

    /**
     * Read from config which css and js files to load, and add them to the output.
     * If `AssetCompress` plugin is loaded, use the `asset_compress.ini` configuration
     * that is part of this plugin.
     *
     * @return void
     */
    protected function _loadAssets()
    {
        if (Configure::read('CrudView.useAssetCompress')) {
            $this->AssetCompress->css('CrudView.crudview', ['block' => true]);
            $this->AssetCompress->script('CrudView.crudview_head', ['block' => 'headjs']);
            $this->AssetCompress->script('CrudView.crudview', ['block' => true]);
            return;
        }

        $config = Configure::read('CrudView');
        if (!$config) {
            return;
        }

        if (!empty($config['css'])) {
            $this->Html->css($config['css'], ['block' => true]);
        }

        if (!empty($config['js'])) {
            foreach ($config['js'] as $block => $scripts) {
                $this->Html->script($scripts, ['block' => $block]);
            }
        }
    }

    /**
     * Setup helpers
     *
     * @return void
     */
    protected function _setupHelpers()
    {
        $this->loadHelper('Html', ['className' => 'BootstrapUI.Html']);
        $this->loadHelper('Form', [
            'className' => 'BootstrapUI.Form',
            'widgets' => [
               'datetime' => ['CrudView\View\Widget\DateTimeWidget', 'select']
            ]
        ]);
        $this->loadHelper('Flash', ['className' => 'BootstrapUI.Flash']);
        $this->loadHelper('Paginator', ['className' => 'BootstrapUI.Paginator']);

        $this->loadHelper('CrudView.CrudView');
        $this->loadHelper('BootstrapUI.Flash');

        if (Configure::read('CrudView.useAssetCompress')) {
            $this->loadHelper('AssetCompress.AssetCompress');
        }
    }

    /**
     * Initializes the crud-view template paths
     *
     * @return void
     */
    protected function _setupPaths()
    {
        $paths = Configure::read('App.paths.templates');

        $extraPaths = Configure::read('CrudView.templatePaths');
        if (!empty($extraPaths)) {
            $paths = array_merge($paths, (array)$extraPaths);
        }
        $paths[] = Plugin::classPath('CrudView') . 'Template' . DS;

        Configure::write('App.paths.templates', $paths);
    }

    /**
     * Fetch the content for a block. If a block is
     * empty or undefined '' will be returned.
     *
     * @param string $name Name of the block
     * @param string $default Default text
     * @return string default The block content or $default if the block does not exist.
     * @see ViewBlock::get()
     */
    public function fetch($name, $default = '')
    {
        $viewblock = '';
        $viewblocks = $this->get('viewblocks', []);
        if (!empty($viewblocks[$name])) {
            $viewblock = $this->_createViewblock($viewblocks[$name]);
        }

        $internal = $this->Blocks->get($name, $default);
        return $internal . $viewblock;
    }

    /**
     * Check if a block exists
     *
     * @param string $name Name of the block
     *
     * @return bool
     */
    public function exists($name)
    {
        $viewblocks = $this->get('viewblocks', []);
        return !empty($viewblocks[$name]) || $this->Blocks->exists($name);
    }

    /**
     * Constructs a ViewBlock from an array of configured data
     *
     * @return void
     */
    protected function _createViewblock($data)
    {
        $output = '';
        foreach ($data as $key => $type) {
            if ($type === 'element') {
                $output = $this->element($key);
            } elseif ($type === 'Html::css') {
                $output .= $this->Html->css($key);
            } elseif ($type === 'Html::script') {
                $output .= $this->Html->script($key);
            } else {
                $output .= $key;
            }
        }
        return $output;
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
        if ($this->templatePath === 'Error') {
            return parent::_getViewFileName($name);
        }
        try {
            return parent::_getViewFileName($name);
        } catch (MissingTemplateException $exception) {
            $this->subDir = null;
            $this->templatePath = 'Scaffold';
            return parent::_getViewFileName($this->template);
        }
    }
}
