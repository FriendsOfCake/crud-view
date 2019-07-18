<?php if ($formSubmitExtraLeftButtons) : ?>
<div class="col pull-left">
    <?php
    if (!empty($formSubmitExtraLeftButtons)) {
        foreach ($formSubmitExtraLeftButtons as $button) {
            if ($button['type'] === 'button') {
                echo $this->Form->button($button['title'], $button['options']);
            } elseif ($button['type'] === 'link') {
                echo $this->Html->link($button['title'], $button['url'], $button['options']);
            } elseif ($button['type'] === 'postLink') {
                if (is_array($button['url'])) {
                    $button['url'][] = $$viewVar->get($primaryKey);
                }
                echo $this->Form->postLink($button['title'], $button['url'], $button['options']);
            }
        }
    }
    ?>
</div>
<?php endif ?>

<div class="col pull-right">
    <?= $this->Form->button(
        $formSubmitButtonText,
        ['class' => 'btn btn-primary', 'name' => '_save', 'value' => '1']
    ) ?>
    <?php
    if (!empty($formSubmitExtraButtons)) {
        foreach ($formSubmitExtraButtons as $button) {
            if ($button['type'] === 'button') {
                echo $this->Form->button($button['title'], $button['options']);
            } elseif ($button['type'] === 'link') {
                echo $this->Html->link($button['title'], $button['url'], $button['options']);
            }
        }
    }
    ?>
</div>
