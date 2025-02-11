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
 * Контроллер панели запросов.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Request extends Panel
{
    /**
     * Добавляет вкладки на панель формы компонента.
     * 
     * @param array $form Панель формы.
     * @param array|string $title Заголовок вкладки.
     * @param array $data Параметры сообщения.
     * 
     * @return void
     */
    protected function addTabView(array &$form, array|string $title, array $data): void
    {
        $store = [];
        if ($data)
            foreach($data as $key => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = \Gm\Debug\Dumper::dumpAsString($value);
                }
                $store[] = ['name' => $key, 'value' => $value];
            }

        $form[] = [
            'title'       => $data ? $this->t($title) . ' <small>(' . sizeof($data) . ')</small>' : $this->t($title),
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
        if (empty($stick))
            throw new Exception\NotDefinedException(
                $this->t('There is no information about the selected stick')
        );
        $stickItems = $stick->geItemsByPriority(Logger::INFO, 'request');
        if (empty($stickItems))
            throw new Exception\NotDefinedException(
                $this->t('There is no information about the selected stick')
        );

        // панель вкладок формы
        $tabItems = [];
        foreach ($stickItems as $tab) {
            $this->addTabView($tabItems, $tab['message'], $tab['extra']);
        }

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->autoScroll = true;
        $window->form->router->route = Gm::alias('@match', '/form');
        $window->form->router->id = null;
        $window->form->items = [
            "xtype"           => "tabpanel",
            "ui"              => "flat-light",
            "activeTab"       => 0,
            "layout"          => "fit",
            "anchor"          => "100% 100%",
            "cls"             => "g-tab-settings",
            "enableTabScroll" => true,
            "items"           => $tabItems
        ];

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->ui = 'light';
        $window->width = 690;
        $window->height = 500;
        $window->layout = 'fit';
        $window->icon = $window->imageSrc('/icon-request.svg');
        $window->title = $this->t('{request.titleTpl}', [$this->getStickModel()->getIdentifier()]);
        $window->maximizable = true;
        $window->addCss('/toolbar.css');
        $this->setViewToolbar($window);
        return $window;
    }
}
