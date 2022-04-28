<?php

return [
    'order' => [
        'name' => 'name',
        'label' => 'label',
        'comment' => 'comment',
        'amount' => 'amount',
        'accepted_date' => 'order acceptance date',
        'order_date' => 'order pick up date',
        'order_time' => 'order pick up time',
        'manufacturer_id' => 'manufacturer',
        'source_id' => 'source of order acceptance',
        'seller_id' => 'pick up point',
        'file_ids' => 'files',
    ],
    'user' => [
        'username' => 'username',
        'password' => 'password',
        'first_name' => 'first name',
        'last_name' => 'last name',
        'email' => 'email',
        'phone' => 'phone',
        'birth_date' => 'birthday date',
        'role' => 'role',
    ],
    'role' => [
        'name' => 'name',
        'label' => 'label',
        'permissions' => 'permissions',
    ],
];
