Viewblocks are populated from your action via the ``scaffold.viewblocks``
configuration key. Each entry maps the name of a block to a list of content,
where every content item is mapped to its type:

.. code-block:: php

    $action = $this->Crud->action();
    $action->setConfig('scaffold.viewblocks', [
        // The block name, e.g. one of the blocks listed below
        'my_block' => [
            // Renders the given element
            'my-element' => 'element',
            // Renders a css/js file with the HtmlHelper
            'my-styles.css' => 'Html::css',
            'my-script.js' => 'Html::script',
            // Any other value is output as is
            '<p>Some additional content</p>' => 'html',
        ],
    ]);
