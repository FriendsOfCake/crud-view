<?php
declare(strict_types=1);

namespace CrudView\Traits;

use Cake\Core\Configure;

trait CrudViewConfigTrait
{
    /**
     * Make sure the CrudView config exists
     *
     * If it doesn't, load the defaults file
     *
     * @return bool
     */
    public function ensureConfig(): bool
    {
        $config = Configure::read('CrudView');
        if ($config !== null) {
            return $config;
        }

        return Configure::load('CrudView.defaults');
    }
}
