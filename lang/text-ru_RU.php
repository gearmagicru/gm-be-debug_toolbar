<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Пакет русской локализации.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    '{name}'        => 'Панель инструментов отладчика',
    '{description}' => 'Вывод значений переменных, объектов и массивов в структурированном виде в точках отладки',
    '{permissions}' => [
        'any'    => ['Полный доступ', 'Доступ ко всем инструментам отладчика'],
        'view'   => ['Просмотр', 'Просмотр переменных инструментами отладчика'],
        'read'   => ['Чтение', 'Чтение переменных инструментами отладчика'],
        'delete' => ['Удаление', 'Удаление отладочной информации'],
        'clear'  => ['Очистка', 'Удаление всей отладочной информации']
    ],

    // Grid: фильтр
    'none' => '[ без выбора ]',
    // Grid: столбцы
    'Info stick' => 'Информация о стике',
    'Request panel' => 'Панель запроса',
    'Response panel' => 'Панель ответа',
    'Server panel' => 'Панель состояния',
    'Debug panel' => 'Панель отладки',
    'Session panel' => 'Панель сессии пользователя',
    'Preprocessor panel' => 'Панель конфигурации «PHP: препроцессор гипертекста»',
    'Web panel' => 'Панель конфигурации HTTP сервера',
    'Query panel' => 'Панель профилирования запросов',
    'Profiling panel' => 'Панель профилирования производительности',
    'Mail panel' => 'Панель профилирования почты',
    'Date' => 'Дата',
    'Ip address' => 'IP адрес',
    'Name' => 'Имя',
    'Value' => 'Значение',
    'OS' => 'ОС',
    'Operating System' => 'Операционная система',
    'Browser' => 'Браузер',
    'Method' => 'Метод',
    'Request' => 'Запрос',
    'Controller' => 'Контролер',
    'Action' => 'Действие',
    'Module' => 'Модуль',
    'Route' => 'Маршрут',
    'AJAX request' => 'Запрос AJAX (XMLHttpRequest)',
    'ID Stick' => 'ID cтика',
    'Stick' => 'Стик',
    'Stick filename' => 'Имя файла с параметрами запроса пользователя',
    'Status' => 'Статус',
    'Status code' => 'Код состояния',
    'Yes' => 'Да',
    'No' => 'Нет',
    'Close' => 'Закрыть',
    'Additionally' => 'Дополнительно',
    'State' => 'Состояние',
    'Configure' => 'Конфигурация',
    'Inactive' => 'НЕ активна',
    'Active' => 'Активна',
    'Writing user actions to the debugger toolbar - active' => 'Запись действия пользователя в панель инструментов отладчика - Активна',
    'Writing user actions to the debugger toolbar - inactive' => 'Запись действия пользователя в панель инструментов отладчика - НЕ Активна',
    // Grid: сообщения / ошибки
    'ms' => ' мс.',
    '[unknow]' => '[неизвестно]',
    'Unable to retrieve stick information with id {0}, it may have been deleted' => 'Невозможно получить информацию о стике с идентификатором "{0}", возможно он был удален.',
    'No stick {0} file found' => 'Не найден файл стика: {0}.',
    'There is no information about the selected stick' => 'Нет информации о выбранном стике.',
    'No info for debug panel' => 'Нет информации для панели отладки.',
    'Unable to delete tag file: {0}' => 'Невозможно выполнить удаление тег файла: <br>{0}',
    'Unable to delete index file: {0}' => 'Невозможно выполнить удаление индексного файла: {0}',
    'The main debug file "{0}" is not found, specify the correct path' => 'Главный файл отладки "{0}" на найден, укажите правильный путь.',
    'In the debug configuration settings, enable the option to access the repository' => 'В настройках конфигурации отладки, включите опцию доступа к хранилищу',

    // Stick
    '{stick.title}' => 'Нет информации',
    '{stick.titleTpl}' => 'Информация о стике &laquo;{stick}&raquo;',

    // Request
    '{request.titleTpl}' => 'Панель запроса для стика &laquo;{0}&raquo;',
    // Request: поля
    'Session' => 'Сессия',
    'Headers' => 'Заголовки',

    // Response
    '{response.titleTpl}' => 'Панель ответа для стика &laquo;{0}&raquo;',
    // Response: поля
    'Format response' => 'Формат ответа',
    'Response' => 'Ответ',
    'Content' => 'Контент',
    'Raw Content' => 'Контент необработанный',
    'Format' => 'Формат',
    'Status code' => 'Код статуса',
    'Service name' => 'Имя службы',
    'Script packages' => 'Пакеты скриптов клиента',
    'Registered script packages' => 'Зар-е пакеты скриптов клиента',
    'Meta tags' => 'Мета теги',
    'View model' => 'Модель представления',

    // State
    '{server.titleTpl}' => 'Панель состояния для стика &laquo;{0}&raquo;',
    // State: поля
    'Services' => 'Службы',
    'Aliases' => 'Переменные',

    // Debug
    '{debug.titleTpl}' => 'Панель отладки для стика &laquo;{0}&raquo;',
    // Debug: поля
    'Type' => 'Тип',
    'Size' => 'Размер',

    // Session
    '{session.titleTpl}' => 'Панель сессии пользователя',

    // Preprocessor
    '{preprocessor.titleTpl}' => 'Панель конфигурации «PHP: препроцессор гипертекста»',
    
    // Web
    '{web.titleTpl}' => 'Панель конфигурации HTTP сервера',
    // Web: поля
    'Modules' => 'Модули',
    'Server software' => 'Имя сервера',
    'Server version' => 'Версия сервера',
    'Server detail' => 'Детально',
    'Server name' => 'Имя хоста',
    'Protocol' => 'Протокол',
    'Port' => 'Порт',
    'IP address' => 'IP-адрес сервера',

    // Query
    '{query.titleTpl}' => 'Панель профилирования запросов к серверу базы данных для стика "{0}"',
    // Query: поля
    'Time' => 'Время',
    'Duration' => 'Длительность, мс',
    'Query' => 'SQL запрос по шаблону',
    'Raw query' => 'SQL запрос',
    'Query params' => 'Параметры запроса',
    'Query result' => 'Результат запроса',
    'Error' => 'Ошибка',

    // Profiling
    '{profiling.titleTpl}' => 'Панель профилирования производительности для стика &laquo;{0}&raquo;',
    // Profiling: поля
    'Extra' => 'Дополнительные параметры',
    'Category' => 'Категория',
    'Info' => 'Информация',
    'Memory' => 'Память, байт',
    'Memory usage' => 'Количество выделенной памяти / Пиковое значение объема памяти (байт)',
    'Trace' => 'Трассировка',

    // Mail
    '{mail.titleTpl}' => 'Панель профилирования почты для стика &laquo;{0}&raquo;',
    // Mail: поля
    'Mail description' => 'Описание',
    'Message ID' => 'ID Сообщения',
    'Subject' => 'Тема',
    'Address From' => 'От кого',
    'Address To' => 'Кому',
    'The main recipient of the letter' => 'Основной получатель письма',
    'Address Reply-To' => 'Адрес Reply-To',
    'Mailing address' => 'Адрес для ответа на рассылку',
    'Address Bcc' => 'Адрес Bcc',
    'Bcc (blind carbon copy)' => 'Bcc (скрытая копия, blind carbon copy) — скрытые получатели письма, чьи адреса не показываются другим получателям',
    'Address Cc' => 'Адрес Сc',
    'Cc (carbon copy)' => 'Cc (копия, carbon copy) — вторичные получатели письма, которым направляется копия. Они видят и знают о наличии друг друга.',
    'Service params' => 'Параметры службы',
    'Mail body' => 'Текст письма',
    'Debug steps' => 'Шаги сборки письма',

    // Command
    '{command.title}' => 'Выполнить',
    'Command' => 'Выполнить',
    'Executing commands' => 'Выполнение команд'
];
