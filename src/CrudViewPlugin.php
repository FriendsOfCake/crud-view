<?php
declare(strict_types=1);

namespace CrudView;

use Cake\Core\BasePlugin;

/**
 * Plugin class for CrudView
 */
class CrudViewPlugin extends BasePlugin
{
    /**
     * Plugin name.
     *
     * @var string|null
     */
    protected ?string $name = 'CrudView';
    /**
     * Do bootstrapping or not
     *
     * @var bool
     */
    protected bool $bootstrapEnabled = false;

    /**
     * Console middleware
     *
     * @var bool
     */
    protected bool $consoleEnabled = false;

    /**
     * Load routes or not
     *
     * @var bool
     */
    protected bool $routesEnabled = false;
}
