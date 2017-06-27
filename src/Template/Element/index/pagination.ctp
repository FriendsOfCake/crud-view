<div class="row">
    <div class="col-md-12 pagination-wrapper">
        <?php if (!empty($indexFormats)) : ?>
            <div class="download-links">
            Download:
            <?php foreach ($indexFormats as $formatTitle => $formatRoute) : ?>
                <?= $this->Html->link($formatTitle, $formatRoute, [
                    'target' => '_blank'
                ]); ?>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="pagination-container">
            <?php
            if ($this->Paginator->hasPage(2)) {
                echo $this->Paginator->numbers([
                    'prev' => true,
                    'next' => true,
                ]);
            }
            ?>

            <p><?= $this->Paginator->counter('Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total.'); ?></p>
        </div>
    </div>
</div>
