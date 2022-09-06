<?php

return [
    'order' => [
        'name' => 'Название',
        'label' => 'Краткое описание',
        'comment' => 'Комментарий',
        'amount' => 'Количество/вес',
        'decoration' => 'Оформление',
        'accepted_date' => 'Дата принятия',
        'order_date' => 'Дата исполнения',
        'order_time' => 'Время исполнения',
        'manufacturer_id' => 'Производство',
        'source_id' => 'Источник заказа',
        'seller_id' => 'Точка выдачи',
        'files' => 'Файлы',
        'number_external' => 'Внешний номер заказа',
    ],
    'user' => [
        'username' => 'Никнейм',
        'password' => 'Пароль',
        'first_name' => 'Имя',
        'last_name' => 'Фамилия',
        'email' => 'Email',
        'phone' => 'Телефон',
        'birth_date' => 'День рождения',
        'sex' => 'Пол',
        'role' => 'Роль',
    ],
    'role' => [
        'name' => 'Название',
        'label' => 'Краткое описание',
        'permissions' => 'Разрешения',
    ],
    'manufacturer' => [
        'name' => 'Название',
        'address' => 'Адрес',
        'phone' => 'Телефон',
        'email' => 'Email',
        'limit' => 'Ограничение',
    ],
    'manufacturer_date_limit' => [
        'manufacturer_id' => 'Производство',
        'date' => 'Дата, на которую ограничено принятие заказов',
        'dates' => 'Даты, на которые ограничено принятие заказов',
        'limit_type' => 'Тип ограничения',
    ],
    'seller' => [
        'name' => 'Название',
        'phone' => 'Телефон',
        'email' => 'Email',
        'address' => 'Адрес',
        'working_hours' => 'Рабочие часы',
    ],
    'files' => [
        'file' => 'Файл',
    ],
];
