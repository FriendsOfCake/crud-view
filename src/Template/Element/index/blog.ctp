<?php
$titleOptions = [];
$bodyOptions = [];
foreach ($fields as $field => $options) {
    unset($options['td']);
    if ($field === $indexTitleField) {
        $titleOptions = $options;
    } elseif ($field === $indexBodyField) {
        $bodyOptions = $options;
    }
}
?>
<?php foreach (${$viewVar} as $singularVar) : ?>
    <h3><?= $this->CrudView->process($indexTitleField, $singularVar, $titleOptions) ?></h3>
    <div class="blog-content">
        <?= $this->CrudView->process($indexBodyField, $singularVar, $bodyOptions) ?>
    </div>
<?php endforeach; ?>
