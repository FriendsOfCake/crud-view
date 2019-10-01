<?php
declare(strict_types=1);

namespace CrudView\Breadcrumb;

class Breadcrumb
{
    /**
     * The content to be wrapped by `<li>` tags.
     * If the specified $url is a link, then this will
     * also be wrapped by `<a>` tags.
     *
     * @var string|array
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
     * Contains a breadcrumb entry
     *
     * @param string|array $title If provided as a string, it represents the title of the crumb.
     * Alternatively, if you want to add multiple crumbs at once, you can provide an array, with each values being a
     * single crumb. Arrays are expected to be of this form:
     * - *title* The title of the crumb
     * - *link* The link of the crumb. If not provided, no link will be made
     * - *options* Options of the crumb. See description of params option of this method.
     * @param string|array|null $url URL of the crumb. Either a string, an array of route params to pass to
     * Url::build() or null / empty if the crumb does not have a link.
     * @param array $options Array of options. These options will be used as attributes HTML attribute the crumb will
     * be rendered in (a <li> tag by default). It accepts two special keys:
     * - *innerAttrs*: An array that allows you to define attributes for the inner element of the crumb (by default, to
     * the link)
     * - *templateVars*: Specific template vars in case you override the templates provided.
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
     * @return string|array
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
