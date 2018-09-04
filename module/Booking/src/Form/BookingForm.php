<?php
namespace Booking\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class BookingForm extends Form
{
    // Date format used in the booking form
    const DATE_FORMAT = 'Y-m-d\TH:i';

    /**
     * Form constructor adds fields to the form
     *
     * @param string $name Sets the form's name
     */
    public function __construct($name = null)
    {
        parent::__construct('booking');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'username',
            'type' => 'text',
            'options' => [
                'label' => 'Name',
            ],
        ]);

        $this->add([
            'name' => 'reason',
            'type' => 'text',
            'options' => [
                'label' => 'Reason for Visit',
            ],
        ]);

        $this->add([
            'name' => 'start_date',
            'type' => Element\DateTimeLocal::class,
            'options' => [
                'label' => 'Start Date',
                'format' => self::DATE_FORMAT,
            ],
            'attributes' => [
                'min' => date(self::DATE_FORMAT, time()),
                'max' => '2020-01-00T00:00',
                'step' => '1', // minutes
            ],
        ]);

        $this->add([
            'name' => 'end_date',
            'type' => Element\DateTimeLocal::class,
            'options' => [
                'label' => 'End Date',
                'format' => self::DATE_FORMAT,
            ],
            'attributes' => [
                'min' => date(self::DATE_FORMAT, time()),
                'max' => '2025-01-00T00:00',
                'step' => '1', // minutes
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}