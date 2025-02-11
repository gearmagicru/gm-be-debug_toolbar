<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\DebugToolbar\Controller;

use Gm;
use Gm\Exception;
use Gm\Log\Logger;
use Gm\Panel\Widget\InfoWindow;
use Gm\Backend\DebugToolbar\Model\StickModel;

/**
 * Контроллер панели профилирования запросов к базе данных.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Query extends Panel
{
    /**
     * Добавляет вкладки на панель формы компонента.
     * 
     * @param array $form Панель формы.
     * @param array<int, array> $rows Параметры сообщения.
     * 
     * @return void
     */
    protected function addGridView(array &$form, array $rows): void
    {
        $store = [];
        if ($rows) {
            foreach ($rows as $row) {
                $profiling = &$row['extra']['profiling'];
                $extra     = &$row['extra']['extra'];
                $duration = round($profiling['duration'] * 1000, 1);
                // если есть трассировка
                if ($profiling['trace']) {
                    $traceRows = '';
                    $offset = strlen(BASE_PATH);
                    foreach($profiling['trace'] as $trace) {
                        if (isset($trace['file']))
                            $traceRows .= '<div class="gm-debugtoolbar-grid__cell-debug-trace">' 
                                        . substr($trace['file'], $offset) . ' <span>(' . $trace['line'] . ')</span></div>';
                    }
                }
                // определение типа запроса
                $rawQuery = htmlspecialchars($extra['rawSql']);
                if (($pos = strpos($rawQuery, ' ')) !== false) {
                    $type = substr($rawQuery, 0, $pos);
                } else
                    $type = 'UNKNOW';
                $store[] = [
                    'time'     => date('H:i:s', $profiling['time'][0]) . '.' . $profiling['time'][1],
                    'duration' => $duration . $this->t('ms'),
                    'memory'   => $profiling['amountMemory'] . ' / ' . $profiling['peakMemory'],
                    'type'     => $type,
                    'query'    => htmlspecialchars($extra['sql']),
                    'rawQuery' => $rawQuery,
                    'params'   => $extra['params'] ? \Gm\Debug\Dumper::dumpAsString($extra['params']) : '',
                    'result'   => empty($extra['result']) ? '' : \Gm\Debug\Dumper::dumpAsString($extra['result']),
                    'error'    => empty($extra['error']) ? '' : $extra['error'],
                    'trace'    => $traceRows
                ];
            }
        }

        $form[] = [
            'xtype'   => 'gridpanel',
            'anchor'  => '100% 100%',
            'store'  => [
                'fields' => ['time', 'duration', 'memory', 'query', 'rawQuery', 'params', 'result', 'error'],
                'data'   => $store
            ],
            'columns' => [
                ['text' => '#Time', 'tooltip' => '#Time', 'dataIndex' => 'time', 'width' => 110],
                ['text' => '#Duration', 'tooltip' => '#Duration', 'dataIndex' => 'duration', 'width' => 130],
                ['text' => '#Memory', 'tooltip' => '#Memory usage', 'dataIndex' => 'memory', 'width' => 120],
                ['text' => '#Type', 'dataIndex' => 'type', 'width' => 90],
                ['text' => '#Query', 'tooltip' => '#Query', 'dataIndex' => 'query', 'hidden' => true, 'cellWrap' => true, 'flex' => 1],
                ['text' => '#Raw query', 'tooltip' => '#Raw query', 'dataIndex' => 'rawQuery', 'cellWrap' => true, 'flex' => 1],
                ['text' => '#Query params', 'tooltip' => '#Query params', 'dataIndex' => 'params', 'hidden' => true, 'cellWrap' => true, 'flex' => 1],
                ['text' => '#Query result', 'tooltip' => '#Query result',  'dataIndex' => 'result', 'cellWrap' => true, 'width' => 200],
                ['text' => '#Error', 'dataIndex' => 'error', 'cellWrap' => true, 'flex' => 1, 'hidden' => true],
                ['text' => '#Trace', 'dataIndex' => 'trace', 'cellWrap' => true, 'flex' => 1, 'hidden' => true]
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): InfoWindow
    {
        /** @var InfoWindow $window */
        $window = parent::createWidget();

        /** @var StickModel|null $stick */
        $stick = $this->getStickModel()->get();
        if (empty($stick))
            throw new Exception\NotDefinedException(
                $this->t('There is no information about the selected stick')
        );

        $stickItems = $stick->geItemsByPriority(Logger::PROFILING, 'query');
        if (empty($stickItems))
            throw new Exception\NotDefinedException(
                $this->t('There is no information about the selected stick')
        );

        // панель вкладок формы
        $formItems = [];
        $this->addGridView($formItems, $stickItems);

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->router->id = null;
        $window->form->router->route = Gm::alias('@match', '/form');
        $window->form->autoScroll = true;
        $window->form->items = $formItems;

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->ui = 'light';
        $window->width = '80%';
        $window->height = 500;
        $window->layout = 'fit';
        $window->icon = $window->imageSrc('/icon-query.svg');
        $window->title = $this->t('{query.titleTpl}',[$this->getStickModel()->getIdentifier()]);
        $window->maximizable = true;
        $window->addCss('/toolbar.css');
        $this->setViewToolbar($window);
        return $window;
    }
}
