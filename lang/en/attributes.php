<?php

return [
    'order' => [
        'name' => 'Name',
        'label' => 'Label',
        'comment' => 'comment',
        'amount' => 'Amount',
        'accepted_date' => 'Order acceptance date',
        'order_date' => 'Order pick up date',
        'order_time' => 'Order pick up time',
        'manufacturer_id' => 'Manufacturer',
        'source_id' => 'Source of order acceptance',
        'seller_id' => 'Pick up point',
        'file_ids' => 'Files',
    ],
    'user' => [
        'username' => 'Username',
        'password' => 'Password',
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'email' => 'Email',
        'phone' => 'Phone',
        'birth_date' => 'Birthday date',
        'sex' => 'Sex',
        'role' => 'Role',
    ],
    'role' => [
        'name' => 'Name',
        'label' => 'Label',
        'permissions' => 'Permissions',
    ],
    'manufacturer' => [
        'name' => 'Name',
        'address' => 'Address',
        'phone' => 'Phone',
        'email' => 'Email',
        'limit' => 'Limit',
    ],
    'manufacturer_date_limit' => [
        'manufacturer_id' => 'Manufacturer',
        'date' => 'Date limit',
        'dates' => 'Dates limit',
        'limit_type' => 'Limit type',
    ],
    'seller' => [
        'name' => 'Name',
        'phone' => 'Phone',
        'email' => 'Email',
        'address' => 'Address',
        'working_hours' => 'Working hours',
    ],
    'files' => [
        'file' => 'File',
    ],
];
