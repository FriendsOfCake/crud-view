<?php
declare(strict_types=1);

namespace CrudView\View;

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\EventListenerInterface;
use Cake\View\Exception\MissingTemplateException;
use Cake\View\View;
use CrudView\Traits\CrudViewConfigTrait;

/**
 * @property \AssetCompress\View\Helper\AssetCompressHelper $AssetCompress
 */
class CrudView extends View implements EventListenerInterface
{
    use CrudViewConfigTrait;

    /**
     * The name of the layout file to render the view inside of. The name specified
     * is the filename of the layout in /app/Template/Layout without the .ctp
     * extension.
     *
     * @var string
     */
    protected $layout = 'CrudView.default';

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
    public function initialize(): void
    {
        parent::initialize();

        $this->getEventManager()->on($this, ['priority' => 9]);
        $this->ensureConfig();
        $this->_setupPaths();
        $this->_setupHelpers();
    }

    /**
     * Events this class is interested in.
     *
     * @return array
     */
    public function implementedEvents(): array
    {
        return [
            'View.beforeLayout' => 'beforeLayout',
        ];
    }

    /**
     * Handler for View.beforeLayout event.
     *
     * @return void
     */
    public function beforeLayout()
    {
        $this->_loadAssets();
    }

    /**
     * Read from config which css and js files to load, and add them to the output.
     * If `AssetCompress` plugin is loaded, use the `asset_compress.ini` configuration
     * that is part of this plugin.
     *
     * @return void
     */
    protected function _loadAssets(): void
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
    protected function _setupHelpers(): void
    {
        $helpers = [
            'Html' => ['className' => 'BootstrapUI.Html'],
            'Form' => [
                'className' => 'BootstrapUI.Form',
                'widgets' => [
                    'datetime' => ['CrudView\View\Widget\DateTimeWidget', 'select'],
                ],
            ],
            'Flash' => ['className' => 'BootstrapUI.Flash'],
            'Paginator' => ['className' => 'BootstrapUI.Paginator'],
            'CrudView' => ['className' => 'CrudView.CrudView'],
        ];

        if (class_exists('\Cake\View\Helper\BreadcrumbsHelper')) {
            $helpers['Breadcrumbs'] = ['className' => 'BootstrapUI.Breadcrumbs'];
        }

        if (Configure::read('CrudView.useAssetCompress')) {
            $helpers['AssetCompress'] = ['className' => 'AssetCompress.AssetCompress'];
        }

        $this->helpers = array_merge($helpers, $this->helpers);
    }

    /**
     * Initializes the crud-view template paths
     *
     * @return void
     */
    protected function _setupPaths(): void
    {
        $paths = Configure::read('App.paths.templates');

        $extraPaths = Configure::read('CrudView.templatePaths');
        if (!empty($extraPaths)) {
            $paths = array_merge($paths, (array)$extraPaths);
        }
        $paths[] = Plugin::templatePath('CrudView');

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
    public function fetch(string $name, string $default = ''): string
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
    public function exists(string $name): bool
    {
        $viewblocks = $this->get('viewblocks', []);

        return !empty($viewblocks[$name]) || $this->Blocks->exists($name);
    }

    /**
     * Constructs a ViewBlock from an array of configured data
     *
     * @param array $data ViewBlock data
     *
     * @return string
     */
    protected function _createViewblock(array $data): string
    {
        $output = '';
        foreach ($data as $key => $type) {
            if ($type === 'element') {
                $output = $this->element($key);
            } elseif ($type === 'Html::css') {
                $output .= (string)$this->Html->css($key);
            } elseif ($type === 'Html::script') {
                $output .= (string)$this->Html->script($key);
            } else {
                $output .= $key;
            }
        }

        return $output;
    }

    /**
     * Returns filename of given action's template file as a string.
     *
     * @param string|null $name Controller action to find template filename for.
     * @return string Template filename
     * @throws \Cake\View\Exception\MissingTemplateException When a view file could not be found.
     */
    protected function _getTemplateFileName(?string $name = null): string
    {
        if ($this->templatePath === 'Error') {
            return parent::_getTemplateFileName($name);
        }
        try {
            return parent::_getTemplateFileName($name);
        } catch (MissingTemplateException $exception) {
            $this->subDir = '';
            $this->templatePath = 'Scaffold';
        }
        try {
            return parent::_getTemplateFileName($this->template);
        } catch (MissingTemplateException $exception) {
            return parent::_getTemplateFileName($name);
        }
    }
}
