<?php

return [
    'order' => [
        'items' => 'Товары',
        'name' => 'Название',
        'label' => 'Краткое описание',
        'comment' => 'Комментарий',
        'amount' => 'Количество/вес',
        'decoration' => 'Оформление',
        'decoration_type' => 'Тип оформления',
        'accepted_date' => 'Дата принятия',
        'order_date' => 'Дата исполнения',
        'order_time' => 'Время исполнения',
        'manufacturer_id' => 'Производство',
        'source_id' => 'Источник заказа',
        'seller_id' => 'Точка выдачи',
        'files' => 'Файлы',
        'number_external' => 'Внешний номер заказа',
        'phone' => 'Телефон',
        'unit_id' => 'Единица измерения',
        'contact' => [
            'value' => 'Контакт',
            'social' => 'Социальная сеть',
            'phone' => 'Телефон',
        ],
    ],
    'user' => [
        'id' => 'Сотрудник',
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
        'as_pickup_point' => 'В качестве пункта выдачи',
    ],
    'files' => [
        'file' => 'Файл',
    ],
    'activities' => [
        'date_start' => 'Дата от',
        'date_end' => 'Дата до',
    ],
    'order_setting' => [
        'type' => 'Тип',
        'value' => 'Значение',
    ],
    'boards' => [
        'board' => [
            'name' => 'Название',
            'file_id' => 'Файл',
        ],
        'group' => [
            'board_id' => 'Доска',
            'name' => 'Название',
            'sort' => 'Порядковый номер сортировки',
        ],
    ],
];