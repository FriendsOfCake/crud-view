<?php
declare(strict_types=1);

namespace CrudView\Listener\Traits;

trait DeprecatedConfigurationKeyTrait
{
    /**
     * Emit a deprecation notice for deprecated configuration key use
     *
     * @param string $deprecatedKey Name of key that is deprecated
     * @param string $newKey Name of key that should be used instead of the deprecated key
     * @return void
     **/
    protected function deprecatedScaffoldKeyNotice(string $deprecatedKey, strin $newKey): void
    {
        $template = 'The configuration key %s has been deprecated. Use %s instead.';
        $message = sprintf($template, $deprecatedKey, $newKey);
        trigger_error($message, E_USER_DEPRECATED);
    }
}
