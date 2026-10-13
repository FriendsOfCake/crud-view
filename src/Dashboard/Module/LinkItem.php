<?php
declare(strict_types=1);

namespace CrudView\Dashboard\Module;

use InvalidArgumentException;

class LinkItem
{
    // PHPCS does not yet support property hooks (https://github.com/PHPCSStandards/PHP_CodeSniffer/issues/734),
    // so the following sniffs emit false positives for hooked properties.
    // phpcs:disable PSR2.Classes.PropertyDeclaration, Squiz.Scope.MemberVarScope, Generic.WhiteSpace.ScopeIndent, PSR12.Operators.OperatorSpacing
    /**
     * The content to be wrapped by `<a>` tags.
     *
     * @var array|string
     **/
    public string|array $title {
        set(string|array $title) {
            if ($title === '') {
                throw new InvalidArgumentException('Missing title for LinkItem action');
            }

            $this->title = $title;
        }
    }

    /**
     * Cake-relative URL or array of URL parameters, or
     * external URL (starts with http://)
     *
     * @var array|string
     */
    public string|array $url {
        set(string|array $url) {
            if ($url === '') {
                throw new InvalidArgumentException('Invalid url specified for LinkItem');
            }

            $this->url = $url;
        }
    }

    /**
     * Array of options and HTML attributes.
     *
     * @var array
     **/
    public array $options = [] {
        set(array $options) {
            $url = $this->url;
            if (is_string($url) && (str_starts_with($url, 'http://') || str_starts_with($url, 'https://'))) {
                $options += ['target' => '_blank'];
            }

            $this->options = $options;
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
     */
    public function __construct(string|array $title, string|array $url, array $options = [])
    {
        $this->title = $title;
        $this->url = $url;
        $this->options = $options;
    }
}
