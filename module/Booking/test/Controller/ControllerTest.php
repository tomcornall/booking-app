<?php
namespace BookingTest\Controller;

use Booking\Controller\BookingController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class BookingControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            // Grabbing the full application configuration:
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/booking');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Booking');
        $this->assertControllerName(BookingController::class);
        $this->assertControllerClass('BookingController');
        $this->assertMatchedRouteName('booking');
    }
}