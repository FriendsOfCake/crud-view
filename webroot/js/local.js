$(document).on('ready', function() {

    var bulkActionForm = $('.bulk-actions');
    if (bulkActionForm.length) {
        bulkActionForm.submit(function (e) {
            var action = $('.bulk-actions .bulk-action-submit select').val();
            if (!action) {
                return e.preventDefault();
            }

            bulkActionForm.attr('action', action);
        });
    }

    $('[role=datetime-picker]').each(function() {

        var picker = $(this);
        var date = null;

        if (picker.data('timestamp') && picker.data('timezone-offset')) {
            var timezoneOffset = picker.data('timezone-offset');
            date = new Date(picker.data('timestamp') * 1000);

            picker.parents('form').on('submit', function () {
                var timezoneDiff = timezoneOffset + date.getTimezoneOffset();
                var currentDate = picker.data('DateTimePicker').date();
                var convertedDate = currentDate.add(timezoneDiff, 'minutes');
                picker.data('DateTimePicker').date(convertedDate);
            });
        }

        picker.datetimepicker({
            locale: $(this).data('locale'),
            format: $(this).data('format'),
            date: date ? date : picker.val()
        });
    });

    $('select:not(.autocomplete, .no-selectize)').selectize({plugins: ['remove_button']});

    $('input.autocomplete, select.autocomplete').each(function (i, e) {
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
                    error: function() {
                        callback();
                    },
                    success: function(res) {
                        callback($.map(res.data, function (name, id) {
                            return {value: id, text: name};
                        }));
                    }
                });
            }
        });
    });

    $.DirtyForms.dialog = false;
    $('form[data-dirty-check=1]').dirtyForms();

    $('.dropdown-toggle').dropdown();

    // recommended hack to get dropdowns correctly work inside responsive table
    $('.table-responsive').on('show.bs.dropdown', function () {
        $('.table-responsive').css( "overflow", "inherit" );
    });
    $('.table-responsive').on('hide.bs.dropdown', function () {
        $('.table-responsive').css( "overflow", "auto" );
    })
});
