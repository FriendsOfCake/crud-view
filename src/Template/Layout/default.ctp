<!DOCTYPE html>
<html lang="<?= \Locale::getPrimaryLanguage(\Cake\I18n\I18n::locale()) ?>">
<head>
    <?= $this->Html->charset(); ?>
    <title><?= $this->get('title');?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= $this->Html->meta('icon'); ?>
    <?= $this->fetch('meta'); ?>
    <?= $this->fetch('css'); ?>
    <?= $this->fetch('headjs'); ?>
</head>
<body>
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php
                    $siteTitleContent = $siteTitle;
                    if (!empty($siteTitleImage)) {
                        $siteTitleContent = $this->Html->image($siteTitleImage);
                    }
                    if (empty($siteTitleLink)) {
                        echo $this->Html->tag('span', $siteTitleContent, ['class' => 'navbar-brand', 'escape' => false]);
                    } else {
                        echo $this->Html->link($siteTitleContent, $siteTitleLink, ['class' => 'navbar-brand', 'escape' => false]);
                    }
                ?>
            </div>

            <?= $this->element('topbar'); ?>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php if (!empty($disableSidebar)) : ?>
                <div class="col-sm-12">
                    <?= $this->Flash->render(); ?>
                    <?= $this->element('breadcrumbs') ?>
                    <?= $this->fetch('content'); ?>
                    <?= $this->fetch('action_link_forms'); ?>
                </div>
            <?php else : ?>
                <div class="col-xs-0 col-sm-2 col-lg-2">
                    <?= $this->element('sidebar'); ?>
                </div>
                <div class="col-xs-12 col-sm-10 col-lg-10">
                    <?= $this->Flash->render(); ?>
                    <?= $this->element('breadcrumbs') ?>
                    <?= $this->fetch('content'); ?>
                    <?= $this->fetch('action_link_forms'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?= $this->fetch('script'); ?>
</body>
</html>
