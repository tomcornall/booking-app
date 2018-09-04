<?php

namespace BookingRest;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\BookingRestController::class => function($container) {
                    // Load the Controller with the BookingTable object to
                    // for access to the bookings
                    return new Controller\BookingRestController(
                        $container->get(\Booking\Model\BookingTable::class)
                    );
                },
            ],
        ];
    }
}