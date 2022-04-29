<?php

return [
    'accepted' => 'Значение ":attribute" должно быть равно yes, on или 1.',
    'accepted_if' => 'Значение ":attribute" должно быть равно yes, on или 1, когда :other является :value',
    'active_url' => 'Значение ":attribute" должно следовать после :date.',
    'after' => 'Значение ":attribute" должно следовать после :date.',
    'after_or_equal' => 'Значение ":attribute" должно следовать после или быть равно :date.',
    'alpha' => 'Значение ":attribute" должно содержать только алфавитные символы.',
    'alpha_dash' => 'Значение ":attribute" должно содержать только алфавитные символы, цифры, знаки подчеркивания и дефисы.',
    'alpha_num' => 'Значение ":attribute" должно содержать только алфавитные символы и цифры.',
    'array' => 'Значение ":attribute" должно быть массивом.',
    'before' => 'Значение ":attribute" должно следовать до :date.',
    'before_or_equal' => 'Значение ":attribute" должно следовать до или быть равно :date.',
    'between' => [
        'array' => 'Размер ":attribute" должен быть между :min и :max.',
        'file' => 'Размер ":attribute" должен быть между :min и :max килобайт.',
        'numeric' => 'Значение ":attribute" должно быть между :min и :max.',
        'string' => 'Длина ":attribute" должна быть между :min и :max символов.',
    ],
    'boolean' => 'Значение ":attribute" должно быть либо true, либо false.',
    'confirmed' => 'Значение ":attribute" не соответствует требуемому.',
    'current_password' => 'Пароль недействительный.',
    'date' => 'Значение ":attribute" не является валидным значением даты.',
    'date_equals' => 'Значение ":attribute" должно быть датой равной :date.',
    'date_format' => 'Значение ":attribute" не соответствует формату :format.',
    'declined' => 'Значение ":attribute" должно быть равно no, off или 0.',
    'declined_if' => 'Значение ":attribute" должно быть равно no, off или 0, когда :other является :value',
    'different' => 'Значение ":attribute" и :other должны различаться.',
    'digits' => 'Значение ":attribute" должно быть числом длиной :digits цифр.',
    'digits_between' => 'Значение ":attribute" должно быть числом длиной между :min и :max цифр.',
    'dimensions' => 'Изображение ":attribute" не является подходящим.',
    'distinct' => 'Значение ":attribute" содержит дубли.',
    'email' => 'Значение ":attribute" должно быть email адресом.',
    'ends_with' => 'Значение ":attribute" должно оканчиваться на: :values.',
    'enum' => 'Выбранный :attribute не является верным.',
    'exists' => 'Выбранный :attribute не является верным.',
    'file' => 'Значение ":attribute" не является файлом.',
    'filled' => 'Значение ":attribute" должно быть заполненным.',
    'gt' => [
        'array' => 'Массив ":attribute" должен содержать больше :value элементов.',
        'file' => 'Размер ":attribute" должен быть больше :value килобайт.',
        'numeric' => 'Значение ":attribute" должно быть больше :value.',
        'string' => 'Длина ":attribute" должна быть больше :value символов.',
    ],
    'gte' => [
        'array' => 'Массив ":attribute" должен содержать больше или равно :value элементов.',
        'file' => 'Размер ":attribute" должен быть больше или равен :value килобайт.',
        'numeric' => 'Значение ":attribute" должно быть больше или равно :value.',
        'string' => 'Длина ":attribute" должна быть больше или равна :value символов.',
    ],
    'image' => 'Значение ":attribute" должно быть изображением.',
    'in' => 'Значение ":attribute" не подходит.',
    'in_array' => 'Значение ":attribute" не содержится в :other.',
    'integer' => 'Значение ":attribute" должно быть целым числом.',
    'ip' => 'Значение ":attribute" должно быть ip адресом.',
    'ipv4' => 'Значение ":attribute" должно быть IPv4 адресом.',
    'ipv6' => 'Значение ":attribute" должно быть IPv6 адресом.',
    'json' => 'Значение ":attribute" должно быть JSON строкой.',
    'lt' => [
        'array' => 'Массив ":attribute" должен содержать меньше :value элементов.',
        'file' => 'Размер ":attribute" должен быть меньше :value килобайт.',
        'numeric' => 'Значение ":attribute" должно быть меньше :value.',
        'string' => 'Длина ":attribute" должна быть меньше :value символов.',
    ],
    'lte' => [
        'array' => 'Массив ":attribute" не может содержать больше :value элелетнов.',
        'file' => 'Размер ":attribute" не должен превышать :value килобайт.',
        'numeric' => 'Значение ":attribute" должно быть меньше или равно :value.',
        'string' => 'Длина ":attribute" не может быть больше :value символов.',
    ],
    'mac_address' => 'Значение ":attribute" должно быть MAC адресом.',
    'max' => [
        'array' => 'Массив ":attribute" не может содержать больше :max элементов.',
        'file' => 'Размер ":attribute" не может быть больше :max килобайт.',
        'numeric' => 'Значение ":attribute" не может быть больше :max.',
        'string' => 'Длина ":attribute" не может быть больше :max символов.',
    ],
    'mimes' => 'Файл ":attribute" должен быть одним из следующих типов: :values.',
    'mimetypes' => 'Файл ":attribute" должен быть одним из следующих типов: :values.',
    'min' => [
        'array' => 'Массив ":attribute" не может содержать меньше :min элементов.',
        'file' => 'Размер ":attribute" не может быть меньше :min килобайт.',
        'numeric' => 'Значение ":attribute" не может быть меньше :min.',
        'string' => 'Длина ":attribute" не может быть меньше :min символов.',
    ],
    'multiple_of' => 'Значение ":attribute" должно быть нескольким из :value.',
    'not_in' => 'Значение ":attribute" не подходит.',
    'not_regex' => 'Значение ":attribute" неверного формата.',
    'numeric' => 'Значение ":attribute" должно быть числом.',
    'password' => 'Пароль неверный.',
    'present' => 'Значение ":attribute" должно быть присутствовать.',
    // 'prohibited' => 'The :attribute field is prohibited.',
    // 'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    // 'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    // 'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'Значение ":attribute" неверного формата.',
    'required' => 'Поле ":attribute" обязательно для заполнения.',
    'required_array_keys' => 'Значение :attribute должно содержать: :values.',
    'required_if' => 'Значение ":attribute" обязательно когда :other равно :value.',
    'required_unless' => 'Значение ":attribute" обязательно, если только :other не равно :values.',
    'required_with' => 'Значение ":attribute" обязательно когда присутствует хотя бы одно из :values.',
    'required_with_all' => 'Значение ":attribute" обязательно когда присутствуют все значения :values.',
    'required_without' => 'Значение ":attribute" обязательно когда не присутствует хотя бы одно из :values.',
    'required_without_all' => 'Значение ":attribute" обязательно когда отсутствуют :values.',
    'same' => 'Значение ":attribute" и :other должны быть одинаковыми.',
    'size' => [
        'array' => 'Массив ":attribute" должен содержать :size элементов.',
        'file' => 'Размер ":attribute" должен быть равен :size килобайт.',
        'numeric' => 'Значение ":attribute" должно быть размером равным :size.',
        'string' => 'Длина ":attribute" должна быть равна :size символов.',
    ],
    'starts_with' => 'Значение ":attribute" должно начинаться с: :values.',
    'string' => 'Значение ":attribute" должно быть строкой.',
    'timezone' => 'Значение ":attribute" должно быть валидной временной зоной.',
    'unique' => 'Значение ":attribute" должно быть уникальным.',
    'uploaded' => 'Файл ":attribute" не загружен.',
    'url' => 'Значение ":attribute" должно быть валидным URL.',
    'uuid' => 'Значение ":attribute" должно быть валидным UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    'values' => [
        'order_date' => [
            'today' => 'сегодня',
            'tomorrow' => 'завтра',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
];
