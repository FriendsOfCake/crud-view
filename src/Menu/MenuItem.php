<?php
namespace CrudView\Menu;

class MenuItem
{
    /**
     * The content to be wrapped by `<a>` tags.
     *
     * @var string
     **/
    protected $title;

    /**
     * Cake-relative URL or array of URL parameters, or
     * external URL (starts with http://)
     *
     * @var string|array|null
     */
    protected $url = null;

    /**
     * Array of options and HTML attributes.
     *
     * @var array
     **/
    protected $options = [];

    /**
     * Contains an HTML link.
     *
     * @param string $title The content to be wrapped by `<a>` tags.
     * @param string|array|null $url Cake-relative URL or array of URL parameters, or
     *   external URL (starts with http://)
     * @param array $options Array of options and HTML attributes.
     * @return void
     */
    public function __construct($title, $url = null, array $options = [])
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the menu item ur
     *
     * @return string|array|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns the menu item options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
