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

    datetimePicker: function (selector) {
        $(selector).flatpickr();
    },

    selectize: function (selector) {
        $(selector).selectize({plugins: ['remove_button']});
    },

    autocomplete: function (selector) {
        $(selector).each(function (i, e) {
            e = $(e);
            e.selectize({
                maxItems: e.data('max-items') || 1,
                maxOptions: e.data('max-options') || 10,
                hideSelected: e.data('hide-selected'),
                closeAfterSelect: e.data('close-after-select'),
                create: !e.data('exact-match'),
                persist: false,
                render: {
                    'option_create': function(data, escape) {
                        return '<div class="create">🔍 <strong> ' + escape(data.input) + '</strong>&hellip;</div>';
                    }
                },
                load: function (query, callback) {
                    var data = {};

                    data[e.data('filter-field') || e.attr('name')] = query;

                    if (e.data('dependent-on') && $('#' + e.data('dependent-on')).val()) {
                        data[e.data('dependent-on-field')] = $('#' + e.data('dependent-on')).val();
                    }
                    $.ajax({
                        url: e.data('url'),
                        dataType: 'json',
                        data: data,
                        error: function () {
                            callback();
                        },
                        success: function (res) {
                            callback($.map(res.data, function (name, id) {
                                return {value: id, text: name};
                            }));
                        }
                    });
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

    initialize: function () {
        this.bulkActionForm('.bulk-actions');
        this.selectize('select:not(.autocomplete, .no-selectize)');
        this.datetimePicker('.flatpickr');
        this.autocomplete('input.autocomplete, select.autocomplete');
        this.dirtyForms();
        this.dropdown();
    }
};

$(function () {
    CrudView.initialize();
});
