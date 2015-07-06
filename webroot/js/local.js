jQuery(document).on('ready', function() {

  var bulkActionForm = jQuery('.bulk-actions');
  if (bulkActionForm.length) {
    bulkActionForm.submit(function (e) {
      var action = jQuery('.bulk-actions .bulk-action-submit select').val();
      if (!action) {
        return e.preventDefault();
      }

      bulkActionForm.attr('action', action);
    });
  }

  jQuery('[role=datetime-picker]').each(function() {
    $(this).datetimepicker({
      locale: $(this).data('locale'),
      format: $(this).data('format'),
      date: new Date($(this).data('timestamp') * 1000)
    });
  });
});
