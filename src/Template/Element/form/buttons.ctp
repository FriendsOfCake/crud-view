<div class="col pull-right">
    <?php
        echo $this->Form->button(__d('crud', 'Save'), ['class' => 'btn btn-primary', 'name' => '_save']);
        if (empty($disableExtraButtons)) {
            if (!in_array('save_and_continue', $extraButtonsBlacklist)) {
                echo $this->Form->button(__d('crud', 'Save & continue editing'), ['class' => 'btn btn-success btn-save-continue', 'name' => '_edit', 'value' => true]);
            }
            if (!in_array('save_and_create', $extraButtonsBlacklist)) {
                echo $this->Form->button(__d('crud', 'Save & create new'), ['class' => 'btn btn-success', 'name' => '_add', 'value' => true]);
            }
            if (!in_array('back', $extraButtonsBlacklist)) {
                echo $this->Html->link(__d('crud', 'Back'), ['action' => 'index'], ['class' => 'btn btn-default', 'role' => 'button', 'value' => true]);
            }
        }
    ?>
</div>
