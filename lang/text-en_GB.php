<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет английской (британской) локализации
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Debugger toolbar',
    '{description}' => 'Outputting the values of variables, objects and arrays in a structured form at debug points',
    '{permissions}' => [
        'any'    => ['Full access', 'Access to all debugger tools'],
        'view'   => ['View', 'Viewing variables with debugger tools'],
        'read'   => ['Reading', 'Reading variables with debugger tools'],
        'delete' => ['Deleting', 'Removing debug information'],
        'clear'  => ['Clear', 'Removing all debug information']
    ],

    // Grid: фильтр
    'none' => '[ none ]',
    // Grid: столбцы
    'Info stick' => 'Stick info',
    'Request panel' => 'Request panel',
    'Response panel' => 'Response panel',
    'Server panel' => 'Server panel',
    'Debug panel' => 'Debug panel',
    'Session panel' => 'Session panel',
    'Preprocessor panel' => 'Configuration panel «PHP: hypertext preprocessor»',
    'Web panel' => 'HTTP server configuration panel',
    'Query panel' => 'Query profiling panel',
    'Profiling panel' => 'Performance profiling panel',
    'Date' => 'Date',
    'Ip address' => 'Ip address',
    'Name' => 'Name',
    'Value' => 'Value',
    'OS' => 'OS',
    'Operating System' => 'Operating System',
    'Browser' => 'Browser',
    'Method' => 'Method',
    'Request' => 'Request',
    'Controller' => 'Controller',
    'Action' => 'Action',
    'Module' => 'Module',
    'Route' => 'Route',
    'AJAX request' => 'AJAX request (XMLHttpRequest)',
    'ID Stick' => 'ID Stick',
    'Stick' => 'Stick',
    'Stick filename' => 'Stick filename',
    'Status' => 'Status',
    'Status code' => 'Status code',
    'Yes' => 'Yes',
    'No' => 'No',
    'Close' => 'Close',
    'Additionally' => 'Additionally',
    'State' => 'State',
    'Configure' => 'Configure',
    'Inactive' => 'Inactive',
    'Active' => 'Active',
    'Writing user actions to the debugger toolbar - active' => 'Writing user actions to the debugger toolbar - active',
    'Writing user actions to the debugger toolbar - inactive' => 'Writing user actions to the debugger toolbar - inactive',
    // Grid: сообщения / ошибки
    'ms' => ' ms.',
    '[unknow]' => '[unknow]',
    'Unable to retrieve stick information with id {0}, it may have been deleted' => 'Unable to retrieve stick information with id {0}, it may have been deleted.',
    'No stick {0} file found' => 'No stick {0} file found.',
    'There is no information about the selected stick' => 'There is no information about the selected stick.',
    'No info for debug panel' => 'No info for debug panel.',
    'Unable to delete tag file: {0}' => 'Unable to delete tag file: <br>{0}',
    'Unable to delete index file: {0}' => 'Unable to delete index file: {0}',
    'The main debug file "{0}" is not found, specify the correct path' => 'The main debug file "{0}" is not found, specify the correct path.',
    'In the debug configuration settings, enable the option to access the repository' => 'In the debug configuration settings, enable the option to access the repository',

    // Stick
    '{stick.title}' => 'No information',
    '{stick.titleTpl}' => 'Stick info "<b>{stick}</b>"',

    // Request
    '{request.titleTpl}' => 'Request panel for Stick "{0}"',
    // Request: поля
    'Session' => 'Session',
    'Headers' => 'Headers',

    // Response
    '{response.titleTpl}' => 'Request panel for Stick "{0}"',
    // Response: поля
    'Format response' => 'Format response',
    'Response' => 'Response',
    'Content' => 'Content',
    'Raw Content' => 'Raw content',
    'Format' => 'Format',
    'Status code' => 'Status code',
    'Service name' => 'Service name',
    'Script packages' => 'Script packages',
    'Registered script packages' => 'Registered script packages',
    'Meta tags' => 'Meta tags',
    'View model' => 'View model',

    // State
    '{server.titleTpl}' => 'Status bar for stick "{0}"',
    // State: поля
    'Services' => 'Services',
    'Aliases' => 'Aliases',

    // Debug
    '{debug.titleTpl}' => 'Stick debug panel "{0}"',
    // Debug: поля
    'Type' => 'Type',
    'Size' => 'Size',

    // Session
    '{session.titleTpl}' => 'User session panel',

    // Preprocessor
    '{preprocessor.titleTpl}' => 'Configuration panel «PHP: hypertext preprocessor»',
    
    // Web
    '{web.titleTpl}' => 'HTTP server configuration panel',
    // Web: поля
    'Modules' => 'Modules',
    'Server software' => 'Server software',
    'Server version' => 'Server version',
    'Server detail' => 'Server detail',
    'Server name' => 'Server name',
    'Protocol' => 'Protocol',
    'Port' => 'Port',
    'IP address' => 'IP address',

    // Query
    '{query.titleTpl}' => 'Database server query profiling panel for stick "{0}"',
    // Query: поля
    'Time' => 'Time',
    'Duration' => 'Duration, ms',
    'Query' => 'SQL query by pattern',
    'Raw query' => 'SQL query',
    'Query params' => 'Query parameters',
    'Query result' => 'Query result',
    'Error' => 'Error',

    // Profiling
    '{profiling.titleTpl}' => 'Performance profiling panel for stick "{0}"',
    // Profiling: поля
    'Extra' => 'Extra options',
    'Category' => 'Category',
    'Info' => 'Information',
    'Memory' => 'Memory, byte',
    'Memory usage' => 'Amount of allocated memory / Peak value of memory size (bytes)',
    'Trace' => 'Trace',

    // Mail
    '{mail.titleTpl}' => 'Stick mail profiling panel &laquo;{0}&raquo;',
    // Mail: поля
    'Mail description' => 'Mail description',
    'Message ID' => 'Message ID',
    'Subject' => 'Subject',
    'Address From' => 'Address From',
    'Address To' => 'Address To',
    'The main recipient of the letter' => 'The main recipient of the letter',
    'Address Reply-To' => 'Address Reply-To',
    'Mailing address' => 'Mailing address',
    'Address Bcc' => 'Адрес Bcc',
    'Bcc (blind carbon copy)' => 'Bcc (blind carbon copy) - hidden recipients of the letter, whose addresses are not shown to other recipients.',
    'Address Cc' => 'Адрес Сc',
    'Cc (carbon copy)' => 'Cc (copy, carbon copy) - secondary recipients of the letter to whom the copy is sent. They see and know about each other\'s presence.',
    'Service params' => 'Service params',
    'Mail body' => 'Mail body',
    'Debug steps' => 'Debug steps',

    // Command
    '{command.title}' => 'Command',
    'Command' => 'Command',
    'Executing commands' => 'Executing commands'
];
