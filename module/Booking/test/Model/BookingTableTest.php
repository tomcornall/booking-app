<?php
namespace BookingTest\Model;

use Booking\Model\BookingTable;
use Booking\Model\Booking;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use RuntimeException;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

class BookingTableTest extends AbstractHttpControllerTestCase
{
    protected function setUp()
    {
        $this->tableGateway = $this->prophesize(TableGatewayInterface::class);
        $this->bookingTable = new BookingTable($this->tableGateway->reveal());
    }

    public function testFetchAllReturnsAllBookings()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class)->reveal();
        $this->tableGateway->select()->willReturn($resultSet);

        $this->assertSame($resultSet, $this->bookingTable->fetchAll());
    }

    public function testCanDeleteAnBookingByItsId()
    {
        $this->tableGateway->delete(['id' => 123])->shouldBeCalled();
        $this->bookingTable->deleteBooking(123);
    }

    public function testSaveBookingWillInsertNewBookingsIfTheyDontAlreadyHaveAnId()
    {
        $bookingData = [
            'username' => 'Mr Anderson',
            'reason'  => 'Sick with the flu',
            'start_date' => '2020-02-02T00:00',
            'end_date' => '2020-02-02T02:00'
        ];
        $booking = new Booking();
        $booking->exchangeArray($bookingData);

        $this->tableGateway->insert($bookingData)->shouldBeCalled();
        $this->bookingTable->saveBooking($booking);
    }

    public function testSaveBookingWillUpdateExistingBookingsIfTheyAlreadyHaveAnId()
    {
        $bookingData = [
            'id'     => 123,
            'username' => 'Mr Anderson',
            'reason'  => 'Sick with the flu',
            'start_date' => '2020-02-02T00:00',
            'end_date' => '2020-02-02T02:00'
        ];
        $booking = new Booking();
        $booking->exchangeArray($bookingData);

        $resultSet = $this->prophesize(ResultSetInterface::class);
        $resultSet->current()->willReturn($booking);

        $this->tableGateway
            ->select(['id' => 123])
            ->willReturn($resultSet->reveal());
        $this->tableGateway
            ->update(
                array_filter($bookingData, function ($key) {
                    return in_array($key, ['username', 'reason', 'start_date', 'end_date']);
                }, ARRAY_FILTER_USE_KEY),
                ['id' => 123]
            )->shouldBeCalled();

        $this->bookingTable->saveBooking($booking);
    }

    public function testExceptionIsThrownWhenGettingNonExistentBooking()
    {
        $resultSet = $this->prophesize(ResultSetInterface::class);
        $resultSet->current()->willReturn(null);

        $this->tableGateway
            ->select(['id' => 123])
            ->willReturn($resultSet->reveal());

        $this->expectException(
            RuntimeException::class,
            'Could not find row with identifier 123'
        );
        $this->bookingTable->getBooking(123);
    }
}