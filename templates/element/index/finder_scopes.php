<?php
if (empty($indexFinderScopes)) {
    return;
}

$finder = $this->request->query('finder');
foreach ($indexFinderScopes as $indexFinderScope) {
    $scopeOptions = ['class' => 'btn btn-secondary btn-sm', 'role' => 'button'];
    $scopeFinder = $indexFinderScope['finder'];

    if (empty($finder) && $scopeFinder === 'all') {
        $scopeOptions['class'] .= ' active';
    } elseif ($finder == $scopeFinder) {
        $scopeOptions['class'] .= ' active';
    }

    $scopeUrl = ['?' => ['finder' => $scopeFinder]];
    if ($scopeFinder === 'all') {
        $scopeUrl = [];
    }
    echo $this->Html->link($indexFinderScope['title'], $scopeUrl, $scopeOptions);
}
