<div class="collapse navbar-collapse navbar-ex1-collapse navbar-left bs-sidebar">
    <nav>
        <ul class="nav nav-pills nav-stacked">
            <?= $this->cell('CrudView.TablesList', [
                'tables' => \Cake\Utility\Hash::get($actionConfig, 'scaffold.tables'),
                'blacklist' => \Cake\Utility\Hash::get($actionConfig, 'scaffold.tables_blacklist')
            ]) ?>
        </ul>
    </nav>
</div>
