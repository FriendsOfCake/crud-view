<?php
declare(strict_types=1);

namespace CrudView\Dashboard\Module;

use Cake\Datasource\EntityTrait;
use InvalidArgumentException;

class LinkItem
{
    use EntityTrait;

    /**
     * The content to be wrapped by `<a>` tags.
     *
     * @var string
     **/
    protected string $title;

    /**
     * Cake-relative URL or array of URL parameters, or
     * external URL (starts with http://)
     *
     * @var array|string|null
     */
    protected string|array|null $url = null;

    /**
     * Array of options and HTML attributes.
     *
     * @var array
     **/
    protected array $options = [];

    /**
     * Constructor
     *
     * @param array|string $title The content to be wrapped by `<a>` tags.
     *   Can be an array if $url is null. If $url is null, $title will be used as both the URL and title.
     * @param array|string|null $url Cake-relative URL or array of URL parameters, or
     *   external URL (starts with http://)
     * @param array $options Array of options and HTML attributes.
     */
    public function __construct(string|array $title, string|array|null $url, array $options = [])
    {
        $this->set('title', $title);
        $this->set('url', $url);
        $this->set('options', $options);
    }

    /**
     * title property setter
     *
     * @param array|string|null $title A title for the link
     * @return array|string
     */
    protected function _setTitle(string|array|null $title): string|array
    {
        if (empty($title)) {
            throw new InvalidArgumentException('Missing title for LinkItem action');
        }

        return $title;
    }

    /**
     * url property setter
     *
     * @param array|string|null $url Cake-relative URL or array of URL parameters, or
     *   external URL (starts with http://)
     * @return array|string
     */
    protected function _setUrl(string|array|null $url): string|array
    {
        if ($url === null || empty($url)) {
            throw new InvalidArgumentException('Invalid url specified for LinkItem');
        }

        return $url;
    }

    /**
     * options property setter
     *
     * @param array $options Array of options and HTML attributes.
     * @return array|string
     */
    protected function _setOptions(array $options): string|array
    {
        if (empty($options)) {
            $options = [];
        }

        $url = $this->get('url');
        if (!is_array($url)) {
            $isHttp = substr($url, 0, 7) === 'http://';
            $isHttps = substr($url, 0, 8) === 'https://';
            if ($isHttp || $isHttps) {
                $options += ['target' => '_blank'];
            }
        }

        return $options;
    }
}
