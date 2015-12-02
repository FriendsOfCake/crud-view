<?php
namespace CrudView\Traits;

use Cake\Core\Configure;

trait CrudViewConfigTrait
{
    /**
     * Make sure the CrudView config exists
     *
     * If it doesn't, load the defaults file
     *
     * @return array
     */
    public function ensureConfig()
    {
        $config = Configure::read('CrudView');
        if ($config !== null) {
            return $config;
        }

        return Configure::load('CrudView.defaults');
    }
}
