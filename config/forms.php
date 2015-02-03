<?php
$config = [
    'fieldset' => '{{content}}',

    'formGroup' => '{{label}}<div class="col-sm-10">{{input}}{{error}}</div>',

    'groupContainer' => '<div class="input {{type}}{{required}} form-group">{{content}}</div>',
    'groupContainerError' => '<div class="input {{type}}{{required}} form-group has-error">{{content}}</div>',

    'input' => '<input type="{{type}}" name="{{name}}" {{attrs}}>',
    'error' => '<div class="help-block error-message">{{content}}</div>',

    'checkboxFormGroup' => '{{label}}<div class="col-sm-10"><div class="checkbox">{{input}}</div></div>',
];
