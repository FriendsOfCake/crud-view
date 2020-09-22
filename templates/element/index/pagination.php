<div class="row">
    <div class="col-md-12 pagination-wrapper">
        <?= $this->element('index/download_formats', compact('indexFormats')); ?>

        <div class="pagination-container">
            <?php
            if ($this->Paginator->hasPage(2)) {
                echo $this->Paginator->links([
                    'first' => true,
                    'last' => true,
                    'prev' => true,
                    'next' => true,
                ]);
            }
            ?>

            <p><?= $this->Paginator->counter('Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total.'); ?></p>
        </div>
    </div>
</div>
