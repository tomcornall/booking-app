<?php
namespace BookingRestTest\Controller;

use Booking\Model\Booking;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class BookingRestControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $bookingTableMock;

    protected function setUp() {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../../config/application.config.php'
        );

        $this->bookingTableMock = $this->getBookingTableMock();
        $this->useBookingTableMock($this->bookingTableMock);
    }

    public function testGetListCanBeAccessed() {
        $this->bookingTableMock
            ->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue(array()));

        $this->dispatch('/booking-rest', 'GET', array(), true);

        $this->assertResponseStatusCode(200);
        $this->assertIsBookingRestController();
    }

    public function testGetCanBeAccessed() {
        $this->bookingTableMock->expects($this->once())
            ->method('getBooking')
            ->will($this->returnValue(array()));

        $this->dispatch('/booking-rest', 'GET', array(
            'id' => 1
        ), true);

        $this->assertResponseStatusCode(200);
        $this->assertIsBookingRestController();
    }

    public function testCreateCanBeAccessed() {
        $data = array(
            'artist' => 'foo',
            'title' => 'bar'
        );

        $this->bookingTableMock
            ->expects($this->once())
            ->method('saveBooking')
            ->with($this->withBookingData($data))
            ->will($this->returnValue(123));

        $this->dispatch('/booking-rest', 'POST', $data, true);

        $this->assertResponseStatusCode(200);
        $this->assertIsBookingRestController();
    }

    public function testUpdateCanBeAccessed() {
        $data_orig = array(
            'artist' => 'foo',
            'title' => 'bar'
        );

        $updateData = array(
            'title' => 'shazaam'
        );

        // Mock BookingTable::getBooking
        $this->bookingTableMock
            ->expects($this->once())
            ->method('getBooking')
            ->will($this->returnValue($data_orig));

        // Mock BookingTable::saveBooking
        $this->bookingTableMock
            ->expects($this->once())
            ->method('saveBooking')
            ->with($this->withBookingData(array(
                'artist' => 'foo',
                'title' => 'shazaam'
            )))
            ->will($this->returnValue(123));

        $this->dispatch('/booking-rest/1', 'PUT', $updateData, true);

        $this->assertResponseStatusCode(200);
        $this->assertIsBookingRestController();
    }

    public function testDeleteCanBeAccessed() {
        $this->bookingTableMock
            ->expects($this->once())
            ->method('deleteBooking')
            ->with(123);

        $this->dispatch('/booking-rest/123', 'DELETE', null, true);

        $this->assertResponseStatusCode(200);
        $this->assertIsBookingRestController();
    }

    /*
     * HELPER METHODS
     */

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getBookingTableMock() {
        return $this->getMockBuilder('Booking\Model\BookingTable')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function useBookingTableMock(\PHPUnit_Framework_MockObject_MockObject $bookingTableMock) {
        $this->getApplicationServiceLocator()
            ->setAllowOverride(true)
            ->setService('Booking\Model\BookingTable', $bookingTableMock);
    }

    protected function assertIsBookingRestController() {
        $this->assertControllerName('BookingRest\Controller\BookingRest');
        $this->assertControllerClass('BookingRestController');
        $this->assertMatchedRouteName('booking-rest');
    }

    protected function withBookingData($data) {
        return $this->callback(function ($obj) use ($data) {
            return $obj instanceof Booking &&
            $obj->artist === $data['artist'] &&
            $obj->title === $data['title'];
        });

    }

}