<?php

namespace Booking;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\BookingTable::class => function($container) {
                    // Construct the BookingTable with a table gateway object
                    $tableGateway = $container->get(Model\BookingTableGateway::class);
                    return new Model\BookingTable($tableGateway);
                },
                Model\BookingTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Booking());
                    return new TableGateway('booking', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\BookingController::class => function($container) {
                    return new Controller\BookingController(
                        $container->get(Model\BookingTable::class)
                    );
                },
            ],
        ];
    }
}