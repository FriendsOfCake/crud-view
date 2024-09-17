<?php
declare(strict_types=1);

namespace CrudView\Breadcrumb;

class ActiveBreadcrumb extends Breadcrumb
{
    // phpcs:ignore
    /**
     * // phpcs:ignore
     * @inheritDoc
     * @psalm-suppress MissingParamType
     */
    public function __construct(string|array $title, string|array|null $url = null, array $options = [])
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
