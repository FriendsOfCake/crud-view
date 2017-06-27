<?php
if (empty($indexFinderScopes)) {
    return;
}

$finder = $this->request->query('finder');
foreach ($indexFinderScopes as $scopeTitle => $scopeFinder) {
    $scopeOptions = ['class' => 'btn btn-default btn-sm', 'role' => 'button'];

    if (empty($finder) && $scopeFinder === 'all') {
        $scopeOptions['class'] .= ' active';
    } elseif ($finder == $scopeFinder) {
        $scopeOptions['class'] .= ' active';
    }

    $scopeUrl = ['?' => ['finder' => $scopeFinder]];
    if ($scopeFinder === 'all') {
        $scopeUrl = [];
    }
    echo $this->Html->link($scopeTitle, $scopeUrl, $scopeOptions);
}
