<?php
namespace BookingTest\Controller;

use Booking\Controller\BookingController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Booking\Model\BookingTable;
use Zend\ServiceManager\ServiceManager;
use Booking\Model\Booking;
use Prophecy\Argument;

class BookingControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    protected $bookingTable;

    public function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));
        parent::setUp();

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

    /**
     * Simple Index controller action test
     */
    public function testIndexActionCanBeAccessed()
    {
        $this->bookingTable->fetchAll()->willReturn(['test']);

        $this->dispatch('/booking');
        $this->assertResponseStatusCode(200);
        $this->validateModuleClassNames();

        $this->dispatch('/booking/add');
        $this->assertResponseStatusCode(200);
        $this->validateModuleClassNames();

        $this->dispatch('/booking/edit');
        $this->assertResponseStatusCode(200);
        $this->validateModuleClassNames();

        $this->dispatch('/booking/delete');
        $this->assertResponseStatusCode(200);
        $this->validateModuleClassNames();
    }

    /**
     * Helper for validating class and module names after a dispatch
     */
    protected function validateModuleClassNames()
    {
        $this->assertModuleName('Booking');
        $this->assertControllerName(BookingController::class);
        $this->assertControllerClass('BookingController');
        $this->assertMatchedRouteName('booking');
    }

    public function testAddActionRedirectsAfterValidPost()
    {
        $this->bookingTable
            ->saveBooking(Argument::type(Booking::class))
            ->shouldBeCalled();

        $postData = [
            'username'  => 'Mr Anderson',
            'reason' => 'Exploding body',
            'start_date' => '2020-02-02T00:00',
            'end_date' => '2020-02-02T02:00',
            'id'     => '',
        ];

        $this->dispatch('/booking/add', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/booking');
    }

    public function testEditActionRedirectsAfterValidPost()
    {
        $this->bookingTable->getBooking()->willReturn(new Booking());

        $postData = [
            'username'  => 'Mr Anderson',
            'reason' => 'Exploding body',
            'start_date' => '2020-02-02T00:00',
            'end_date' => '2020-02-02T02:00'
        ];

        $this->dispatch("/booking/edit/1", 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/booking');
    }

    public function testDeleteActionRedirectsAfterValidPost()
    {
        $this->bookingTable->getBooking()->willReturn(new Booking());

        $postData = [
            'username'  => 'Mr Anderson',
            'id' => 1
        ];

        $this->dispatch("/booking/delete/1", 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/booking');
    }
}