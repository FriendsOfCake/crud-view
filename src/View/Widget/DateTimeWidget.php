<?php
namespace CrudView\View\Widget;

use Cake\View\Form\ContextInterface;
use Cake\View\Widget\DateTimeWidget as CoreDateTimeWidget;

class DateTimeWidget extends CoreDateTimeWidget
{

    public function render(array $data, ContextInterface $context)
    {
        return '
			<div class="col-sm-10">
				<div class="input-group date" id="datetimepicker-' . $data['id'] . '" data-date-format="YYYY-MM-DD HH:mm:ss">
					<input type="text" class="form-control" value="' . $data['val']->format('Y-m-d H:i:s') . '" name="' . $data['name'] . '" />
					<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
				</div>
			</div>

			<script type="text/javascript">
			jQuery(function() {
				$("#datetimepicker-' . $data['id'] . '").datetimepicker({
					sideBySide: true,
					useSeconds: false
				});
			});
			</script>';
    }
}

