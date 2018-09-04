<?php

namespace Booking;

use Zend\Router\Http\Segment;
// use Booking\Controller\BookingController;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'booking' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/booking[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\BookingController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'booking-rest' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/rest[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\BookingRestController::class,
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'booking' => __DIR__ . '/../view',
        ],
    ],
];