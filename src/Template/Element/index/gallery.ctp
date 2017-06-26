<?php
$actionsExist = !empty($actions['entity']);
$titleOptions = [];
$bodyOptions = [];
$imageOptions = [];
foreach ($fields as $field => $options) {
    unset($options['td']);
    if ($field === $indexTitleField) {
        $titleOptions = $options;
    } elseif ($field === $indexBodyField) {
        $bodyOptions = $options;
    } elseif ($field === $indexImageField) {
        $imageOptions = $options;
    }
}
?>
<div class="row">
    <?php foreach (${$viewVar} as $singularVar) : ?>
        <div class="<?= $indexGalleryCssClasses ?>">
            <?php
                $imageAltContent = $this->CrudView->fieldValue($singularVar, $indexTitleField);
                $titleContent = $this->CrudView->process($indexTitleField, $singularVar, $titleOptions);
                $bodyContent = $this->CrudView->process($indexBodyField, $singularVar, $bodyOptions);
                $imageContent = $this->CrudView->process($indexImageField, $singularVar, $bodyOptions);
            ?>
            <div class="thumbnail">
                <?php
                    if (empty($imageContent)) {
                        $imageContent = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
                    }
                    echo $this->Html->image($imageContent, $imageOptions + ['alt' => $imageAltContent]);
                ?>
                <div class="caption gallery-content">
                    <h3 class="text-truncate"><?= $titleContent ?></h3>

                    <?php if (!empty($bodyContent)) :?>
                        <p><?= $bodyContent ?></p>
                    <?php endif; ?>

                    <?php if ($actionsExist): ?>
                        <p><?= $this->element('actions', [
                            'singularVar' => $singularVar,
                            'actions' => $actions['entity']
                        ]); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
