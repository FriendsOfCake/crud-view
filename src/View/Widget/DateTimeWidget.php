<?php
namespace CrudView\View\Widget;

use Cake\I18n\I18n;
use Cake\I18n\Time;
use Cake\View\Form\ContextInterface;
use DateTime;

class DateTimeWidget extends \Cake\View\Widget\DateTimeWidget
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
        $type = $data['type'];
        $required = $data['required'] ? 'required' : '';
        $timestamp = $year = $month = $day = $hour = $minute = false;
        $locale = locale_get_primary_language(I18n::locale());
        $format = $type === 'date' ? 'Y-m-d' : 'Y-m-d H:i:s';
        if (isset($data['data-format'])) {
            $format = $data['data-format'];
        }

        if (!$val instanceof DateTime && !empty($val)) {
            $val = Time::parseDateTime($val);
        }

        if ($val) {
            $year = $val->format('Y');
            $month = $val->format('m') - 1;
            $day = $val->format('d');
            if ($type !== 'date') {
                $hour = $val->format('H');
                $minute = $val->format('i');
            }
            $val = $val->format($format);
            $timestamp = strtotime($val);
        }

        $format = $this->_convertPHPToMomentFormat($format);

        $widget = <<<html
            <div class="input-group $type">
                <input
                    type="text"
                    class="form-control"
                    name="$name"
                    value="$val"
                    id="$id"
                    role="datetime-picker"
                    data-locale="$locale"
                    data-format="$format"
                    data-timestamp="$timestamp"
                    $required
                />
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
html;
        return $widget;
    }

    /**
     * Converts PHP date format to one supported by MomentJS.
     *
     * @param string $format PHP date format.
     * @return string MomentJS date format.
     * @see http://stackoverflow.com/a/30192680
     */
    protected function _convertPHPToMomentFormat($format)
    {
        $replacements = [
            'd' => 'DD',
            'D' => 'ddd',
            'j' => 'D',
            'l' => 'dddd',
            'N' => 'E',
            'S' => 'o',
            'w' => 'e',
            'z' => 'DDD',
            'W' => 'W',
            'F' => 'MMMM',
            'm' => 'MM',
            'M' => 'MMM',
            'n' => 'M',
            't' => '', // no equivalent
            'L' => '', // no equivalent
            'o' => 'YYYY',
            'Y' => 'YYYY',
            'y' => 'YY',
            'a' => 'a',
            'A' => 'A',
            'B' => '', // no equivalent
            'g' => 'h',
            'G' => 'H',
            'h' => 'hh',
            'H' => 'HH',
            'i' => 'mm',
            's' => 'ss',
            'u' => 'SSS',
            'I' => '', // no equivalent
            'O' => '', // no equivalent
            'P' => '', // no equivalent
            'T' => '', // no equivalent
            'Z' => '', // no equivalent
            'c' => '', // no equivalent
            'r' => '', // no equivalent
            'U' => 'X',
        ];
        $momentFormat = strtr($format, $replacements);
        return $momentFormat;
    }
}
