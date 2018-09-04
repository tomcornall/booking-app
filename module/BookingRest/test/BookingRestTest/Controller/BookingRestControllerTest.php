<?php
namespace BookingRestTest\Controller;

use Booking\Model\Booking;
use Booking\Model\BookingTable;
use Zend\ServiceManager\ServiceManager;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class BookingRestControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    protected $bookingTable;

    protected function setUp() {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../../config/application.config.php'
        );

        $this->configureServiceManager($this->getApplicationServiceLocator());
    }

    protected function configureServiceManager(ServiceManager $services)
    {
        $services->setAllowOverride(true);

        $services->setService('config', $this->updateConfig($services->get('config')));
        $services->setService(BookingTable::class, $this->mockBookingTable()->reveal());

        $services->setAllowOverride(false);
    }

    protected function updateConfig($config)
    {
        $config['db'] = [];
        return $config;
    }

    protected function mockBookingTable()
    {
        $this->bookingTable = $this->prophesize(BookingTable::class);
        return $this->bookingTable;
    }

    public function testGetListCanBeAccessed() {
        $this->bookingTable
            ->fetchAll()
            ->willReturn(['test']);

        $this->dispatch('/api/booking', 'GET', [], true);

        $this->assertResponseStatusCode(200);
        $this->assertIsBookingRestController();
    }

    public function testGetCanBeAccessed() {
        $this->bookingTable
            ->getBooking(1)
            ->willReturn(new Booking());

        $this->dispatch('/api/booking', 'GET', [
            'id' => 1
        ], true);

        $this->assertResponseStatusCode(200);
        $this->assertIsBookingRestController();
    }

    public function testCreateCanBeAccessed() {
        $data = [
            'username' => 'Johnson',
            'reason' => 'Back pain',
            'start_date' => 'test',
            'end_date' => 'test',
        ];

        $this->bookingTable
            ->saveBooking($data)
            ->willReturn('test');

        $this->dispatch('/api/booking', 'POST', $data, true);

        $this->assertResponseStatusCode(200);
        $this->assertIsBookingRestController();
    }

    public function testUpdateCanBeAccessed() {
        $data_orig = [
            'username' => 'Test Man',
            'reason' => 'Shoulder twinge',
            'start_date' => '2020-02-02T00:00',
            'end_date' => '2020-02-02T02:00',
        ];

        $updateData = [
            'reason' => 'Back pain',
            'start_date' => '2020-02-02T00:00',
            'end_date' => '2020-02-02T02:00',
        ];

        $this->bookingTable
            ->getBooking(1)
            ->willReturn(new Booking());

        $this->bookingTable
            ->saveBooking([
                'id' => 1,
                'username' => 'Test Man',
                'reason' => 'Back pain',
                'start_date' => '2020-02-02T00:00',
                'end_date' => '2020-02-02T02:00'
            ])
            ->willReturn('test');

        $this->dispatch('/api/booking/1', 'PUT', $updateData, true);

        $this->assertResponseStatusCode(200);
        $this->assertIsBookingRestController();
    }

    public function testDeleteCanBeAccessed() {
        $this->bookingTable
            ->deleteBooking(1)
            ->willReturn('test');

        $this->dispatch('/api/booking/1', 'DELETE', null, true);

        $this->assertResponseStatusCode(200);
        $this->assertIsBookingRestController();
    }

    protected function assertIsBookingRestController() {
        $this->assertControllerName('BookingRest\Controller\BookingRestController');
        $this->assertControllerClass('BookingRestController');
        $this->assertMatchedRouteName('booking-rest');
    }

    protected function withBookingData($data) {
        return $this->callback(function ($obj) use ($data) {
            return $obj instanceof Booking &&
                $obj->username === $data['username'] &&
                $obj->reason === $data['reason'] &&
                $obj->start_date === $data['start_date'] &&
                $obj->end_date === $data['end_date'];
        });
    }
}