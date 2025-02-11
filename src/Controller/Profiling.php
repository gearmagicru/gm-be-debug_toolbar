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
 * Контроллер панели профилирования производительности.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Profiling extends Panel
{
    /**
     * Добавляет вкладки на панель формы компонента.
     * 
     * @param array $form Панель формы.
     * @param array $rows Параметры сообщения.
     * 
     * @return void
     */
    protected function addGridView(array &$form, array $rows): void
    {
        $store = [];
        if ($rows) {
            foreach($rows as $row) {
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
                $store[] = [
                    'time'     => date('H:i:s', $profiling['time'][0]) . '.' . $profiling['time'][1],
                    'duration' => $duration . $this->t('ms'),
                    'category' => $row['category'],
                    'memory'   => $profiling['amountMemory'] . ' / ' . $profiling['peakMemory'],
                    'info'     => $row['message'],
                    'extra'    => $extra ? \Gm\Debug\Dumper::dumpAsString($extra) : '',
                    'trace'    => $traceRows
                ];
            }
        }
        $form[] = [
            'xtype'  => 'gridpanel',
            'anchor' => '100% 100%',
            'store'  => [
                'fields' => ['time', 'duration', 'category', 'memory', 'info', 'extra'],
                'data'   => $store
            ],
            'columns' => [
                ['text' => '#Time', 'tooltip' => '#Time', 'dataIndex' => 'time', 'width' => 110],
                ['text' => '#Duration', 'tooltip' => '#Duration', 'dataIndex' => 'duration', 'width' => 130],
                ['text' => '#Category', 'tooltip' => '#Category', 'dataIndex' => 'category', 'width' => 120],
                ['text' => '#Memory', 'tooltip' => '#Memory usage','dataIndex' => 'memory', 'width' => 120],
                ['text' => '#Info', 'tooltip' => '#Info', 'dataIndex' => 'info', 'cellWrap' => true, 'flex' => 1],
                ['text' => '#Extra', 'tooltip' => '#Extra', 'dataIndex' => 'extra',  'hidden' => true, 'cellWrap' => true, 'flex' => 1],
                ['text' => '#Trace', 'tooltip' => '#Info', 'dataIndex' => 'trace', 'cellWrap' => true, 'flex' => 1],
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

        $stickItems = $stick->geItemsByPriority(Logger::PROFILING);
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
        $window->width = '70%';
        $window->height = 500;
        $window->layout = 'fit';
        $window->icon = $window->imageSrc('/icon-profiling.svg');
        $window->title = $this->t('{profiling.titleTpl}', [$this->getStickModel()->getIdentifier()]);
        $window->maximizable = true;
        $window->addCss('/toolbar.css');
        $this->setViewToolbar($window);
        return $window;
    }
}
