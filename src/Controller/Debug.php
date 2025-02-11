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
use Gm\Debug\Dumper;
use Gm\Panel\Widget\InfoWindow;
use Gm\Backend\DebugToolbar\Model\StickModel;

/**
 * Контроллер панели запросов.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Debug extends Panel
{
    /**
     * Возвращает отладочную информацию.
     * 
     * @param array $debug Переменные.
     * 
     * @return
     */
    protected function getGridView(array $debug): array
    {
        $store = [];
        foreach ($debug as $var) {
            $value = $var['extra'];
            if (is_object($value) || is_array($value)) {
                $value = Dumper::dumpAsString($value);
            }
            $store[] = ['name' => $var['message'], 'value' => $value];
        }

        return [
            'bodyPadding' => 0,
            'layout'      => 'fit',
            'items'       => [
                'xtype'  => 'gridpanel',
                'anchor' => '100% 100%',
                'store'  => [
                    'fields' => ['name', 'value'],
                    'data'   => $store
                ],
                'columns' => [
                    ['text' => '#Name', 'dataIndex' => 'name', 'flex' => 1],
                    ['text' => '#Value', 'dataIndex' => 'value', 'flex' => 2]
                ]
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
        if (empty($stick)) {
            throw new Exception\NotDefinedException($this->t('There is no information about the selected stick'));
        }

        $stickItems = $stick->geItemsByPriority(Logger::DEBUG);
        if (empty($stick->debug) || empty($stickItems)) {
            throw new Exception\NotDefinedException($this->t('No info for debug panel'));
        }

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->router->id = null;
        $window->form->router->route = Gm::alias('@match', '/form');
        $window->form->autoScroll = true;
        $window->form->bodyPadding = 10;
        $window->form->loadJSONFile('/debug-form', 'items', [
            '@storeData' => $stick->debugExport()
        ]);

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->ui = 'light';
        $window->width = 800;
        $window->height = 400;
        $window->layout = 'fit';
        $window->icon = $window->imageSrc('/icon-debug.svg');
        $window->title = $this->t('{debug.titleTpl}', [$stick->getIdentifier()]);
        $window->maximizable = true;
        $window->addCss('/toolbar.css');
        $this->setViewToolbar($window);

        return $window;
    }
}
