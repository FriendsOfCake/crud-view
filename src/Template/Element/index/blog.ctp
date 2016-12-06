<?php
$titleOptions = [];
$bodyOptions = [];
foreach ($fields as $field => $options) {
    unset($options['td']);
    if ($field === $indexBlogTitleField) {
        $titleOptions = $options;
    } elseif ($field === $indexBlogBodyField) {
        $bodyOptions = $options;
    }
}
?>
<?php foreach (${$viewVar} as $singularVar) : ?>
    <h3><?= $this->CrudView->process($indexBlogTitleField, $singularVar, $titleOptions) ?></h3>
    <div class="blog-content">
        <?= $this->CrudView->process($indexBlogBodyField, $singularVar, $bodyOptions) ?>
    </div>
<?php endforeach; ?>
