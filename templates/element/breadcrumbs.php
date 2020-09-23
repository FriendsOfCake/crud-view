<?php
if (empty($breadcrumbs)) {
    return;
}
?>
<?php foreach ($breadcrumbs as $breadcrumb) : ?>
    <?php $this->Breadcrumbs->add($breadcrumb->getTitle(), $breadcrumb->getUrl(), $breadcrumb->getOptions()); ?>
<?php endforeach; ?>
<?= $this->Breadcrumbs->render(); ?>
