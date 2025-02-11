<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации модуля.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'translator' => [
        'locale'   => 'auto',
        'patterns' => [
            'text' => [
                'basePath' => __DIR__ . '/../lang',
                'pattern'  => 'text-%s.php'
            ]
        ],
        'autoload' => ['text'],
        'external' => [BACKEND]
    ],

    'accessRules' => [
        // для авторизованных пользователей Панели управления
        [ // разрешение "Полный доступ" (any: view, read, delete, clear)
            'allow',
            'permission'  => 'any',
            'controllers' => [
                'Grid'         => ['data', 'view', 'delete', 'clear', 'filter'],
                'Stick'        => ['data', 'view', 'delete'],
                'Request'      => ['data', 'view'],
                'Server'       => ['data', 'view'],
                'Response'     => ['data', 'view'],
                'Debug'        => ['data', 'view'],
                'Mail'         => ['data', 'view'],
                'Session'      => ['data', 'view'],
                'Web'          => ['data', 'view'],
                'Query'        => ['data', 'view'],
                'Profiling'    => ['data', 'view'],
                'Preprocessor' => ['data', 'view'],
                'Command'      => ['view']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Просмотр" (view)
            'allow',
            'permission'  => 'view',
            'controllers' => [
                'Grid'         => ['data', 'view', 'filter'],
                'Stick'        => ['data', 'view'],
                'Request'      => ['data', 'view'],
                'Server'       => ['data', 'view'],
                'Response'     => ['data', 'view'],
                'Debug'        => ['data', 'view'],
                'Mail'         => ['data', 'view'],
                'Session'      => ['data', 'view'],
                'Web'          => ['data', 'view'],
                'Query'        => ['data', 'view'],
                'Profiling'    => ['data', 'view'],
                'Preprocessor' => ['data', 'view']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Чтение" (read)
            'allow',
            'permission'  => 'read',
            'controllers' => [
                'Grid'         => ['data'],
                'Stick'        => ['data'],
                'Request'      => ['data'],
                'Server'       => ['data'],
                'Response'     => ['data'],
                'Debug'        => ['data'],
                'Mail'         => ['data'],
                'Session'      => ['data'],
                'Web'          => ['data'],
                'Query'        => ['data'],
                'Profiling'    => ['data'],
                'Preprocessor' => ['data']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Удаление" (delete)
            'allow',
            'permission'  => 'delete',
            'controllers' => [
                'Grid'  => ['delete'],
                'Stick' => ['delete']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Очистка" (clear)
            'allow',
            'permission'  => 'clear',
            'controllers' => [
                'Grid' => ['clear']
            ],
            'users' => ['@backend']
        ],
        [ // разрешение "Информация о модуле" (info)
            'allow',
            'permission'  => 'info',
            'controllers' => ['Info'],
            'users'       => ['@backend']
        ],
        [ // разрешение "Настройка модуля" (settings)
            'allow',
            'permission'  => 'settings',
            'controllers' => ['Settings'],
            'users'       => ['@backend']
        ],
        [ // для всех остальных, доступа нет
            'deny'
        ]
    ],

    'toolbar' => [
        'stick'    => ['text' => '#Info stick'],
        'request'  => ['text' => '#Request panel'],
        'response' => ['text' => '#Response panel'],
        'server'   => ['text' => '#Server panel'],
        'debug'    => ['text' => '#Debug panel'],
        'mail'     => ['text' => '#Mail panel'],
        'query'    => ['text' => '#Query panel'],
        'profiling' => ['text' => '#Profiling panel'],
    ],

    'viewManager' => [
        'id'          => 'gm-debugtoolbar-{name}',
        'useTheme'    => false,
        'useLocalize' => true,
        'viewMap'     => [
            // информации о модуле
            'info' => [
                'viewFile'      => '//backend/module-info.phtml', 
                'forceLocalize' => true
            ],
            'stickForm'    => '/stick-form.json',
            'serverForm'   => '/server-form.json',
            'debugForm'    => '/debug-form.json',
            'mailForm'     => '/mail-form.json',
            'responseForm' => '/response-form.json',
            'sessionForm'  => '/session-form.json'
        ]
    ]
];
