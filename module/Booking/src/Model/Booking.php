<?php
namespace Booking\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;
use Zend\Validator\Date;

class Booking implements InputFilterAwareInterface
{
    /**
     * Date format for bookings
     */
    const BOOKING_DATE_FORMAT = 'Y-m-d\TH:i';

    /**
     * Booking attributes
     */
    public $id;
    public $username;
    public $reason;
    public $start_date;
    public $end_date;

    /**
     * Input filter
     */
    private $inputFilter;

    /**
     * Exchange Array for zend entity class
     */
    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->username = !empty($data['username']) ? $data['username'] : null;
        $this->reason  = !empty($data['reason']) ? $data['reason'] : null;
        $this->start_date  = !empty($data['start_date']) ? $data['start_date'] : null;
        $this->end_date  = !empty($data['end_date']) ? $data['end_date'] : null;
    }

    /**
     * Array copy method
     *
     * @return array Booking object attributes
     */
    public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'username' => $this->username,
            'reason'  => $this->reason,
            'start_date'  => $this->start_date,
            'end_date'  => $this->end_date,
        ];
    }

    /**
     * Sets Input Filter
     *   - Inherited from the InputFilter interface
     *   - We don't need to allow injection of alternate filters so we throw an exception
     *
     * @param  InputFilterInterface $inputFilter
     * @return DomainException
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException("Booking Model does not allow injection of an alternate input filter");
    }

    /**
     * Gets the InputFilter for use in validation
     *
     * @return Zend\InputFilter\InputFilter
     */
    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'username',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 80,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'reason',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 255,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'start_date',
            'required' => true,
            'validators' => [
                [
                    'name' => Date::class,
                    'options' => [
                        'format' => self::BOOKING_DATE_FORMAT
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'end_date',
            'required' => true,
            'validators' => [
                [
                    'name' => Date::class,
                    'options' => [
                        'format' => self::BOOKING_DATE_FORMAT
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}