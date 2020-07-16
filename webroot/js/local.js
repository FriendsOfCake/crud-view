var CrudView = {
    bulkActionForm: function (selector) {
        var bulkActionForm = $(selector);
        if (bulkActionForm.length) {
            bulkActionForm.submit(function (e) {
                var action = $('.bulk-action-submit select', bulkActionForm).val();
                if (!action) {
                    return e.preventDefault();
                }

                bulkActionForm.attr('action', action);
            });
        }
    },

    flatpickr: function (selector) {
        $(selector).flatpickr();
    },

    select2: function (selector) {
        $(selector).each(function () {
            var $this = $(this),
                config = {theme: 'bootstrap4'};

            if (!$this.prop('multiple') && $this.find('option:first').val() === '') {
                config.allowClear = true;
                config.placeholder = '';
            }

            $(this).select2(config);
        });
    },

    autocomplete: function (selector) {
        $(selector).each(function (i, ele) {
            var $ele = $(ele);

            $ele.select2({
                theme: 'bootstrap4',
                minimumInputLength: 1,
                ajax: {
                    delay: 250,
                    url: $ele.data('url'),
                    dataType: 'json',
                    data: function (params) {
                        var query = {};
                        query[$ele.data('filter-field') || $ele.attr('name')] = params.term;

                        if ($ele.data('dependent-on') && $('#' + $ele.data('dependent-on')).val()) {
                            data[$ele.data('dependent-on-field')] = $('#' + $ele.data('dependent-on')).val();
                        }

                        return query;
                    },
                    processResults: function (data, params) {
                        var results = [],
                            inputType = $ele.data('inputType'),
                            term = params.term.toLowerCase();

                        if (data.data) {
                            $.each(data.data, function(id, text) {
                                if (text.toLowerCase().indexOf(term) > -1) {
                                    results.push({
                                        id: inputType === 'text' ? text : id,
                                        text: text
                                    });
                                }
                            });
                        }

                        return {
                            results: results
                        };
                    }
                }
            });
        });
    },

    dirtyForms: function () {
        $.DirtyForms.dialog = false;
        $('form[data-dirty-check=1]').dirtyForms();
    },

    dropdown: function () {
        $('.dropdown-toggle').dropdown();

        // recommended hack to get dropdowns correctly work inside responsive table
        $('.table-responsive').on('show.bs.dropdown', function () {
            $('.table-responsive').css( "overflow", "inherit" );
        });
        $('.table-responsive').on('hide.bs.dropdown', function () {
            $('.table-responsive').css( "overflow", "auto" );
        })
    },

    tooltip: function () {
        $('[data-toggle="tooltip"]').tooltip();
    },

    initialize: function () {
        this.bulkActionForm('.bulk-actions');
        this.flatpickr('.flatpickr');
        this.select2('select[multiple]:not(.no-select2), select.select2');
        this.autocomplete('input.autocomplete, select.autocomplete');
        this.dirtyForms();
        this.dropdown();
        this.tooltip();
    }
};

$(function () {
    CrudView.initialize();
});
