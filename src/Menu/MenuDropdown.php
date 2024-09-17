<?php
declare(strict_types=1);

namespace CrudView\Menu;

class MenuDropdown
{
    /**
     * The name of the dropdown
     *
     * @var string
     **/
    protected string $title;

    /**
     * Array of MenuDivider|MenuItem entries
     *
     * @var array
     **/
    protected array $entries = [];

    /**
     * Contains an HTML link.
     *
     * @param string $title The name of the dropdown
     * @param array $entries Array of MenuDivider|MenuItem entries
     */
    public function __construct(string $title, array $entries = [])
    {
        $this->title = $title;
        $this->entries = $entries;
    }

    /**
     * Returns the menu item dropdown title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Returns the menu item dropdown entries
     *
     * @return array
     */
    public function getEntries(): array
    {
        return $this->entries;
    }
}
