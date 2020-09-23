<?php
declare(strict_types=1);

namespace CrudView\View\Widget;

use Cake\Core\Configure;
use Cake\View\Form\ContextInterface;

class DateTimeWidget extends \BootstrapUI\View\Widget\DateTimeWidget
{
    // phpcs:disable
    /**
     * @var string
     */
    protected $defaultTemplate = '<div {{attrs}}>'
        . '{{input}}'
        . '<div class="input-group-append">'
        . '<button data-toggle title="Toggle" type="button" class="btn input-group-text">{{toggleIcon}}</button>'
        . '<button data-clear title="Clear" type="button" class="btn input-group-text">{{clearIcon}}</button>'
        . '</div>'
        . '</div>';

    /**
     * @var string
     */
    protected $calendarIcon = '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calendar" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'
        . '<path fill-rule="evenodd" d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>'
        . '</svg>';

    /**
     * @var string
     */
    protected $clockIcon = '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clock" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'
        . '<path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm8-7A8 8 0 1 1 0 8a8 8 0 0 1 16 0z"/>'
        . '<path fill-rule="evenodd" d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>'
        . '</svg>';

    /**
     * @var string
     */
    protected $clearIcon = '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'
        . '<path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>'
        . '<path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>'
        . '</svg>';
    // phpcs:enable

    /**
     * Render flatpickr
     *
     * @param array $data Data
     * @param \Cake\View\Form\ContextInterface $context Context.
     * @return string
     */
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

        $data = $this->mergeDefaults($data, $context) + ['data-input' => ''];

        $data['value'] = $this->formatDateTime($data['val'], $data);
        unset($data['val'], $data['timezone']);

        // This is the format for value POSTed to server
        $datetimePicker['data-date-format'] = $this->convertPHPToDatePickerFormat($this->formatMap[$data['type']]);

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

        $clearIcon = $this->clearIcon;
        $toggleIcon = $this->calendarIcon;
        if (isset($datetimePicker['iconClass'])) {
            $toggleIcon = $datetimePicker['iconClass'];
            unset($datetimePicker['iconClass']);
        } elseif ($data['type'] === 'time') {
            $toggleIcon = $this->clockIcon;
        }

        if ($this->_templates->get('datetimePicker') === null) {
            $this->_templates->add(['datetimePicker' => $this->defaultTemplate]);
        }

        $data = $this->_templates->addClass($data, 'form-control');
        $wrap = $datetimePicker['data-wrap'] === 'true';
        if ($wrap) {
            if (isset($data['class'])) {
                $datetimePicker['data-alt-input-class'] = $data['class'];
            }
            $datetimePicker['class'] = ['input-group', 'flatpickr'];
            if (isset($data['datetimePicker'])) {
                $datetimePicker = $data['datetimePicker'] + $datetimePicker;
                unset($data['datetimePicker']);
            }
        } else {
            $data += $datetimePicker;
            $data = $this->_templates->addClass($data, 'flatpickr');
        }

        /**
         * @psalm-suppress PossiblyInvalidArrayOffset
         * @psalm-suppress PossiblyInvalidArgument
         */
        $input = $this->_templates->format('input', [
            'name' => $data['name'],
            'type' => 'text',
            'templateVars' => $data['templateVars'],
            'attrs' => $this->_templates->formatAttributes(
                $data,
                ['name', 'type']
            ),
        ]);

        if (!$wrap) {
            return $input;
        }

        /** @psalm-suppress PossiblyInvalidArrayOffset */
        return $this->_templates->format('datetimePicker', [
            'input' => $input,
            'toggleIcon' => $toggleIcon,
            'clearIcon' => $clearIcon,
            'templateVars' => $data['templateVars'],
            'attrs' => $this->_templates->formatAttributes(
                $datetimePicker,
                ['toggleIcon', 'clearIcon']
            ),
        ]);
    }

    /**
     * Converts PHP date format to one supported by flatpickr.
     *
     * @param string $format PHP date format.
     * @return string flatpickr date format.
     * @see https://flatpickr.js.org/formatting/
     */
    protected function convertPHPToDatePickerFormat(string $format): string
    {
        $replacements = [
            's' => 'S',
            'A' => 'K',
        ];
        $datePickerFormat = strtr($format, $replacements);

        return $datePickerFormat;
    }
}
