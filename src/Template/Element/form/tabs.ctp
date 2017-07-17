<div class="nav-tabs-custom">
    <ul class="nav nav-tabs" role="tablist">
        <?php $firstTab = key($formTabGroups) ?>
        <?php foreach ($formTabGroups as $group => $groupFields) : ?>
            <li role="presentation" <?= $group === $firstTab ? 'class="active"' : '' ?>>
                <a href="#tab-<?= mb_strtolower(Text::slug($group)) ?>" role="tab" data-toggle="tab"><?= $group ?></a>
            </li>
        <?php endforeach ?>
    </ul>
    <div class="tab-content">
        <?php foreach ($formTabGroups as $group => $groupFields) : ?>
            <div
                id="tab-<?= mb_strtolower(Text::slug($group)) ?>"
                role="tabpanel"
                class="tab-pane <?= $group === $firstTab ? 'active' : '' ?>"
            >
                <?= $this->Form->inputs($groupFields, ['legend' => false]) ?>
            </div>
        <?php endforeach ?>
    </div>
</div>
