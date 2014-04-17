<?php
namespace CrudView\View\Widget;

class DateTime extends \Cake\View\Widget\DateTime {

	public function render(array $data) {
		return '
		 	<div class="input-group date" id="datetimepicker-' . $data['id'] . '" data-date-format="YYYY-MM-DD HH:mm:ss">
				<input type="text" class="form-control" value="' . $data['val']->format('Y-m-d H:i:s') .'" name="' . $data['name'] . '" />
				<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
			</div>

      <script type="text/javascript">
      jQuery(function() {
				$("#datetimepicker-' . $data['id'] . '").datetimepicker({
					language: "da",
					sideBySide: true,
					useSeconds: false
				});
      });
  		</script>
  		';
	}

}
