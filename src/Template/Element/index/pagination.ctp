<div class="row">
    <div class="col-md-12">

        <?php
        if ($this->Paginator->hasPage(2)) {
            echo $this->Paginator->numbers();
        }
        ?>

        <br />

        <?= $this->Paginator->counter('Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total.'); ?>
    </div>
</div>
