<?php
declare(strict_types=1);

namespace CrudView\Dashboard\Module;

use Cake\Collection\Collection;

class ActionLinkItem extends LinkItem
{
    // PHPCS does not yet support property hooks (https://github.com/PHPCSStandards/PHP_CodeSniffer/issues/734),
    // so the following sniffs emit false positives for hooked properties.
    // phpcs:disable PSR2.Classes.PropertyDeclaration, Squiz.Scope.MemberVarScope
    /**
     * Array of LinkItems.
     *
     * @var array
     **/
    public array $actions = [] {
        set(array $actions) {
            $this->actions = (new Collection($actions))->map(function ($value) {
                $options = (array)$value->options + ['class' => ['btn btn-secondary']];
                $value->options = $options;

                return $value;
            })->toArray();
        }
    }

    // phpcs:enable

    /**
     * Constructor
     *
     * @param array|string $title The content to be wrapped by `<a>` tags.
     *   Can be an array if $url is null. If $url is null, $title will be used as both the URL and title.
     * @param array|string $url Cake-relative URL or array of URL parameters, or
     *   external URL (starts with http://)
     * @param array $options Array of options and HTML attributes.
     * @param array $actions Array of ActionItems
     */
    public function __construct(string|array $title, string|array $url, array $options = [], array $actions = [])
    {
        parent::__construct($title, $url, $options);

        $this->actions = $actions;
    }
}
