<?php
declare(strict_types=1);

namespace CrudView\Dashboard\Module;

use Cake\Collection\Collection;

class ActionLinkItem extends LinkItem
{
    /**
     * Array of LinkItems.
     *
     * @var array
     **/
    protected array $actions = [];

    /**
     * Constructor
     *
     * @param array|string $title The content to be wrapped by `<a>` tags.
     *   Can be an array if $url is null. If $url is null, $title will be used as both the URL and title.
     * @param array|string|null $url Cake-relative URL or array of URL parameters, or
     *   external URL (starts with http://)
     * @param array $options Array of options and HTML attributes.
     * @param array $actions Array of ActionItems
     */
    public function __construct(string|array $title, string|array|null $url, array $options = [], array $actions = [])
    {
        parent::__construct($title, $url, $options);
        $this->set('actions', $actions);
    }

    /**
     * options property setter
     *
     * @param array $actions Array of options and HTML attributes.
     * @return array
     */
    protected function _setActions(array $actions): array
    {
        return (new Collection($actions))->map(function ($value) {
            $options = (array)$value->get('options') + ['class' => ['btn btn-secondary']];
            $value->set('options', $options);

            return $value;
        })->toArray();
    }
}
