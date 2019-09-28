<?php

declare(strict_types=1);

namespace CrudView\View\Cell;

use Cake\View\Cell;

/**
 * DashboardTable cell
 */
class DashboardTableCell extends Cell
{
    /**
     * Default display method.
     *
     * If the first argument is an array, it will replace the second
     * `$links` argument and `$title` will be set to an empty string.
     *
     * @param string|array $title A title to display
     * @param array $links A array of LinkItem objects
     * @return void
     */
    public function display($title, array $links = [])
    {
        if (is_array($title)) {
            $links = $title;
            $title = '';
        }

        $this->set('title', $title);
        if (!empty($links)) {
            $this->set('links', $links);
        }
    }
}
