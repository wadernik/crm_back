<?php

return [
    'status' => [
        'created' => 'New order',
        'taken' => 'Processing',
        'delivery' => 'In delivery',
        'sold' => 'Sold',
        'canceled' => 'Canceled',
        'printed' => 'Printed',
        'to_reprint' => 'To reprint',
    ],
    'limit_reached' => 'The manufacturer has reached the limit on the amount of orders'
        . ' it can accept on the selected date.',
    'limited_date' => 'There is a restriction on accepting orders by the manufacturer on selected date.',
    'not_suitable_seller' => "Current seller can't be used as pick-up point.",
    'contact' => [
        'social' => 'Social',
        'phone' => 'Phone',
        'invalid_type' => 'Invalid type provided.'
    ],
    'settings' => [
        'status_timeout' => 'Order was not processed for a long time.',
    ],
    'item' => [
        'decoration' => [
            'sweets' => 'With sweets',
            'cream' => 'With cream',
            'cream_mastic' => 'With cream / mastic',
            'mastic' => 'With mastic',
        ],
    ],
];