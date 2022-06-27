<?php
declare(strict_types=1);

namespace CrudView\Menu;

class MenuItem
{
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
     * Contains an HTML link.
     *
     * @param string $title The content to be wrapped by `<a>` tags.
     * @param array|string|null $url Cake-relative URL or array of URL parameters, or
     *   external URL (starts with http://)
     * @param array $options Array of options and HTML attributes.
     */
    public function __construct(string $title, string|array|null $url = null, array $options = [])
    {
        $this->title = $title;
        $this->url = $url;
        $this->options = $options;
    }

    /**
     * Returns the menu item title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Returns the menu item ur
     *
     * @return array|string|null
     */
    public function getUrl(): string|array|null
    {
        return $this->url;
    }

    /**
     * Returns the menu item options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
