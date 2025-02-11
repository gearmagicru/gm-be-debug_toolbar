<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации установки модуля
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'use'         => BACKEND,
    'id'          => 'gm.be.debug_toolbar',
    'name'        => 'Debugger toolbar',
    'description' => 'Outputting the values of variables, objects and arrays in a structured form at debug points',
    'namespace'   => 'Gm\Backend\DebugToolbar',
    'path'        => '/gm/gm.be.debug_toolbar',
    'route'       => 'debugtoolbar',
    'routes'      => [
        ['type'    => 'crudSegments',
              'options' => [
                  'module'      => 'gm.be.debug_toolbar',
                  'route'       => 'debugtoolbar',
                  'prefix'      => BACKEND,
                  'constraints' => ['id'],
                  'defaults'    => [
                      'controller' => 'grid'
                  ]
              ]
        ]
    ],
    'locales'     => ['ru_RU', 'en_GB'],
    'permissions' => ['any', 'view', 'read', 'delete', 'clear', 'settings', 'info'],
    'events'      => [],
    'required'    => [
        ['php', 'version' => '8.2'],
        ['app', 'code' => 'GM MS'],
        ['app', 'code' => 'GM CMS'],
        ['app', 'code' => 'GM CRM'],
    ]
];
