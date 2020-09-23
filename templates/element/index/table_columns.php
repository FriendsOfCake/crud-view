<?php
foreach ($fields as $field => $options) {
    $tdOptions = $options['td'] ?? [];
    unset($options['td']);

    echo $this->Html->tag('td', $this->CrudView->process($field, $singularVar, $options), $tdOptions);
}
