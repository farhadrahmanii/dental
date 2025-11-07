<?php

return [

    'title' => 'نوم لیکنه',

    'heading' => 'حساب جوړ کړئ',

    'actions' => [

        'login' => [
            'before' => 'یا',
            'label' => 'خپل حساب ته ننوځئ',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'بریښنالیک پته',
        ],

        'name' => [
            'label' => 'نوم',
        ],

        'password' => [
            'label' => 'پټنوم',
            'validation_attribute' => 'پټنوم',
        ],

        'password_confirmation' => [
            'label' => 'پټنوم تصدیق کړئ',
        ],

        'actions' => [

            'register' => [
                'label' => 'حساب جوړ کړئ',
            ],

        ],

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'ډیر د نوم لیکنې هڅې',
            'body' => 'مهرباني وکړئ په :seconds ثانیو کې بیا هڅه وکړئ.',
        ],

    ],

];

