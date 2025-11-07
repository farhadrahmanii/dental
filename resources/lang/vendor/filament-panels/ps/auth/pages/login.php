<?php

return [

    'title' => 'ننوتل',

    'heading' => 'ننوتل',

    'actions' => [

        'register' => [
            'before' => 'یا',
            'label' => 'حساب جوړ کړئ',
        ],

        'request_password_reset' => [
            'label' => 'پټنوم هیر شوی؟',
        ],

    ],

    'form' => [

        'email' => [
            'label' => 'بریښنالیک پته',
        ],

        'password' => [
            'label' => 'پټنوم',
        ],

        'remember' => [
            'label' => 'ما یاد ساتل',
        ],

        'actions' => [

            'authenticate' => [
                'label' => 'ننوتل',
            ],

        ],

    ],

    'multi_factor' => [

        'heading' => 'خپل پیژندنه تصدیق کړئ',

        'subheading' => 'د ننوتلو دوام لپاره، تاسو باید خپل پیژندنه تصدیق کړئ.',

        'form' => [

            'provider' => [
                'label' => 'تاسو څنګه تصدیق کول غواړئ؟',
            ],

            'actions' => [

                'authenticate' => [
                    'label' => 'ننوتل تصدیق کړئ',
                ],

            ],

        ],

    ],

    'messages' => [

        'failed' => 'دا معلومات زموږ د ریکارډونو سره سمون نه لري.',

    ],

    'notifications' => [

        'throttled' => [
            'title' => 'ډیر د ننوتلو هڅې',
            'body' => 'مهرباني وکړئ په :seconds ثانیو کې بیا هڅه وکړئ.',
        ],

    ],

];

