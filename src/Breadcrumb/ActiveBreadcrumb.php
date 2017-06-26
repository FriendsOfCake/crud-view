<?php
namespace CrudView\Breadcrumb;

use CrudView\Breadcrumb\Breadcrumb;

class ActiveBreadcrumb extends Breadcrumb
{
    /**
     * @inheritdoc
     */
    public function __construct($title, $url = null, array $options = [])
    {
        if (!isset($options['class'])) {
            $options['class'] = '';
        }
        $options['class'] = explode(' ', $options['class']);
        $options['class'][] = 'active';
        $options['class'] = trim(implode(' ', array_unique($options['class'])));

        parent::__construct($title, $url, $options);
    }
}
