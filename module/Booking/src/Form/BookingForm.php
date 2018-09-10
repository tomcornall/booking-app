<?php
namespace Booking\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Booking\Model\Booking;

class BookingForm extends Form
{
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
                'format' => Booking::BOOKING_DATE_FORMAT,
            ],
            'attributes' => [
                'min' => self::getFormattedTimeNow(),
                'max' => self::getTimePlusFiveYears(),
                'step' => 'any'
            ],
        ]);

        $this->add([
            'name' => 'end_date',
            'type' => Element\DateTimeLocal::class,
            'options' => [
                'label' => 'End Date',
                'format' => Booking::BOOKING_DATE_FORMAT,
            ],
            'attributes' => [
                'min' => self::getFormattedTimeNow(),
                'max' => self::getTimePlusFiveYears(),
                'step' => 'any'
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

    /**
     * Get a formatted string date for now
     *
     * @return string
     */
    private static function getFormattedTimeNow()
    {
        return date(Booking::BOOKING_DATE_FORMAT, time());
    }

    /**
     * Get a formatted string date for now + 5 years
     *
     * @return string
     */
    private static function getTimePlusFiveYears()
    {
        return date(Booking::BOOKING_DATE_FORMAT, strtotime('+5 years'));
    }
}