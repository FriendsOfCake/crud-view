<?php
namespace CrudView\View\Widget;

use Cake\I18n\I18n;
use Cake\I18n\Time;
use Cake\View\Form\ContextInterface;
use Cake\View\Widget\DateTimeWidget as CoreDateTimeWidget;
use DateTime;

class DateTimeWidget extends CoreDateTimeWidget
{

    /**
     * Renders a date time widget.
     *
     * @param array $data Data to render with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string A generated select box.
     * @throws \RuntimeException When option data is invalid.
     */
    public function render(array $data, ContextInterface $context)
    {
        $id = $data['id'];
        $name = $data['name'];
        $val = $data['val'];
        $required = $data['required'] ? 'required' : '';
        $year = $month = $day = $hour = $minute = 0;
        $lang = locale_get_primary_language(I18n::locale());

        if (!$val instanceof DateTime && !empty($val)) {
            $val = Time::parseDateTime($val);
        }

        if ($val) {
            $year = $val->format('Y');
            $month = $val->format('m') - 1;
            $day = $val->format('d');
            $hour = $val->format('H');
            $minute = $val->format('i');
            $val = $val->format('Y-m-d H:i:s');
        }

        $widget = <<<html
            <div class="input-group datetime">
                <input type='text' class="form-control" name="$name" value="$val" id='$id' $required/>
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <script type="text/javascript">
                $(function () {
                    var widget = $('#$id').parent().datetimepicker({locale: '$lang'});
                    if ($year) {
                        widget.data('DateTimePicker').date(new Date($year, $month, $day, $hour, $minute));
                    }
                });
            </script>
html;
        return $widget;
    }
}
