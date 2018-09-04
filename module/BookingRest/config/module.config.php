<?php

namespace BookingRest;

return [
    'router' => [
        'routes' => [
            'booking-rest' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/api/booking[/:id]',
                    'constraints' => [
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\BookingRestController::class,
                        'action' => null
                    ]
                ]
            ]
        ]
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ]
    ]
];