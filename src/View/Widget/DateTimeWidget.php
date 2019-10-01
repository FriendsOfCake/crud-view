<?php

declare(strict_types=1);

namespace CrudView\View\Widget;

use Cake\Core\Configure;
use Cake\View\Form\ContextInterface;

class DateTimeWidget extends \BootstrapUI\View\Widget\DateTimeWidget
{
    public function render(array $data, ContextInterface $context): string
    {
        $datetimePicker = Configure::read('CrudView.datetimePicker', false);
        if (isset($data['datetimePicker'])) {
            $defaults = $datetimePicker;

            $datetimePicker = $data['datetimePicker'];
            unset($data['datetimePicker']);

            if ($datetimePicker === false) {
                return $this->_withInputGroup($data, $context);
            }

            if (is_array($defaults)) {
                $datetimePicker += $defaults;
            }
        }

        if ($datetimePicker === false) {
            return $this->_withInputGroup($data, $context);
        }

        $datetimePicker += [
            'data-alt-input-class' => '',
            'data-wrap' => 'true',
        ];

        $data += [
            'name' => '',
            'val' => null,
            'type' => 'datetime-local',
            'escape' => true,
            'timezone' => null,
            'templateVars' => [],
            'data-input' => '',
        ];

        $data['value'] = $this->formatDateTime($data['val'], $data);
        unset($data['val'], $data['timezone']);

        // This is the format for value POSTed to server
        $datetimePicker['data-date-format'] = $this->formatMap[$data['type']];
        $datetimePicker['data-date-format'] = $this->convertPHPToDatePickerFormat($datetimePicker['data-date-format']);

        // This just to allow upgrading easier
        if (isset($data['data-format'])) {
            $datetimePicker['data-alt-format'] = $data['data-format'];
            unset($data['data-format']);
        }
        // This is the format that will be display to user
        if (isset($datetimePicker['data-alt-format'])) {
            $datetimePicker['data-alt-format'] = $this->convertPHPToDatePickerFormat(
                $datetimePicker['data-alt-format']
            );
            $datetimePicker['data-alt-input'] = 'true';
        }

        if ($data['type'] === 'time' || $data['type'] === 'datetime-local') {
            if ($data['type'] === 'time') {
                $datetimePicker['data-no-calendar'] = 'true';
            }

            $datetimePicker['data-enable-time'] = 'true';
            $datetimePicker += ['data-enable-seconds' => 'true'];
        }

        $iconClass = 'fa fa-calendar-alt';
        if (isset($datetimePicker['iconClass'])) {
            $iconClass = $data['iconClass'];
        } elseif ($data['type'] === 'time') {
            $iconClass = 'fa fa-clock';
        }

        unset($datetimePicker['iconClass']);

        if ($this->_templates->get('datetimePicker') === null) {
            // phpcs:disable
            $this->_templates->add([
                'datetimePicker' =>
                    '<div {{attrs}}>'
                    . '{{input}}'
                    . '<div class="input-group-append">'
                    . '<button data-toggle type="button" class="btn input-group-text"><i class="' . $iconClass . '"></i></button>'
                    . '<button data-clear type="button" class="btn input-group-text"><i class="fa fa-times"></i></button>'
                    . '</div>'
                    . '</div>',
            ]);
            // phpcs:enable
        }

        $data = $this->_templates->addClass($data, 'form-control');
        $noWrap = false;
        if ($datetimePicker['data-wrap'] === 'true') {
            $datetimePicker['class'] = ['input-group', 'flatpickr'];
        } else {
            $data += $datetimePicker;
            $data = $this->_templates->addClass($data, 'flatpickr');
            $noWrap = true;
        }

        $input = $this->_templates->format('input', [
            'name' => $data['name'],
            'type' => 'text',
            'templateVars' => $data['templateVars'],
            'attrs' => $this->_templates->formatAttributes(
                $data,
                ['name', 'type']
            ),
        ]);

        if ($noWrap) {
            return $input;
        }

        return $this->_templates->format('datetimePicker', [
            'input' => $input,
            'iconClass' => $iconClass,
            'attrs' => $this->_templates->formatAttributes($datetimePicker),
            'templateVars' => $data['templateVars'],
        ]);
    }

    /**
     * Converts PHP date format to one supported by flatpickr.
     *
     * @param string $format PHP date format.
     * @return string flatpickr date format.
     * @see https://flatpickr.js.org/formatting/
     */
    protected function convertPHPToDatePickerFormat(string $format)
    {
        $replacements = [
            's' => 'S',
            'A' => 'K',
        ];
        $datePickerFormat = strtr($format, $replacements);

        return $datePickerFormat;
    }
}
