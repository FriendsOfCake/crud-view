jQuery(document).on('ready', function() {

    jQuery('input.autocomplete').each(function() {
        var _this = jQuery(this);
        var cache = {};
        var url = _this.data('url');
        var update = _this.data('linked-to');

        if (update) {
            update = $('#' + update);
        }

        _this.autocomplete({
            minLength: 0,

            select: function(event, ui) {
                console.log(ui.item);
                _this.val(ui.item ? ui.item.value : this.value);

                if (update) {
                    update.val(ui.item ? ui.item.id : '');
                }
            },

            source: function(request, response) {
                var term = request.term;

                if (cache.hasOwnProperty(term)) {
                    response(cache[term]);
                    return;
                }

                $.getJSON(url, request, function(data, status, xhr) {
                    var result = [];

                    jQuery.each(data.data, function(key, value) {
                        console.log(key, value);
                        result.push({"id": key, "value": value });
                    });

                    console.log(result);
                    cache[term] = result;
                    response(result);
                });
            }
        })
        .focus(function() {
            _this.autocomplete('search');
        });

    });

});
