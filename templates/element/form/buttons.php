<?php if (!empty($formSubmitExtraLeftButtons)) : ?>
<div class="col pull-left">
    <?php
    $btns = [];
    foreach ($formSubmitExtraLeftButtons as $button) {
        if ($button['type'] === 'button') {
            $btns[] = $this->Form->button($button['title'], $button['options']);
        } elseif ($button['type'] === 'link') {
            $btns[] = $this->Html->link($button['title'], $button['url'], $button['options']);
        } elseif ($button['type'] === 'postLink') {
            if (is_array($button['url'])) {
                $button['url'][] = $$viewVar->get($primaryKey);
            }
            $btns[] = $this->Form->postLink($button['title'], $button['url'], $button['options']);
        }
    }
    echo implode('&nbsp;', $btns);
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
                echo '&nbsp;' . $this->Form->button($button['title'], $button['options']);
            } elseif ($button['type'] === 'link') {
                echo '&nbsp;' . $this->Html->link($button['title'], $button['url'], $button['options']);
            }
        }
    }
    ?>
</div>
