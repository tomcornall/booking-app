<?php
namespace BookingTest\Model;

use Booking\Model\Booking;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class BookingTest extends AbstractHttpControllerTestCase
{
    public function testInitialBookingValuesAreNull()
    {
        $booking = new Booking();

        $this->assertNull($booking->username, '"username" should be null by default');
        $this->assertNull($booking->id, '"id" should be null by default');
        $this->assertNull($booking->reason, '"reason" should be null by default');
        $this->assertNull($booking->start_date, '"start_date" should be null by default');
        $this->assertNull($booking->end_date, '"end_date" should be null by default');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $booking = new Booking();

        $data  = [
            'username' => 'some username',
            'id'     => 123,
            'reason'  => 'some reason'
        ];

        $booking->exchangeArray($data);

        $this->assertSame(
            $data['username'],
            $booking->username,
            '"username" was not set correctly'
        );

        $this->assertSame(
            $data['id'],
            $booking->id,
            '"id" was not set correctly'
        );

        $this->assertSame(
            $data['reason'],
            $booking->reason,
            '"reason" was not set correctly'
        );
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $booking = new Booking();

        $booking->exchangeArray([
            'username' => 'some username',
            'id'     => 123,
            'reason'  => 'some reason',
        ]);

        $booking->exchangeArray([]);

        $this->assertNull($booking->username, '"username" should default to null');
        $this->assertNull($booking->id, '"id" should default to null');
        $this->assertNull($booking->reason, '"reason" should default to null');
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $booking = new Booking();

        $data  = [
            'username' => 'some username',
            'id'     => 123,
            'reason'  => 'some reason'
        ];

        $booking->exchangeArray($data);
        $copyArray = $booking->getArrayCopy();

        $this->assertSame($data['username'], $copyArray['username'], '"username" was not set correctly');
        $this->assertSame($data['id'], $copyArray['id'], '"id" was not set correctly');
        $this->assertSame($data['reason'], $copyArray['reason'], '"reason" was not set correctly');
    }

    public function testInputFiltersAreSetCorrectly()
    {
        $booking = new Booking();

        $inputFilter = $booking->getInputFilter();

        $this->assertSame(5, $inputFilter->count());
        $this->assertTrue($inputFilter->has('username'));
        $this->assertTrue($inputFilter->has('id'));
        $this->assertTrue($inputFilter->has('reason'));
        $this->assertTrue($inputFilter->has('start_date'));
        $this->assertTrue($inputFilter->has('end_date'));
    }
}