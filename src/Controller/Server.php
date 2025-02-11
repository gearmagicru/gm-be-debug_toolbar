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
 * Контроллер панели состояния.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Server extends Panel
{
    /**
     * Добавляет вкладки на панель формы компонента.
     * 
     * @param array $form Панель формы.
     * @param string|array $title Заголовок вкладки.
     * @param array $data Параметры сообщения.
     * 
     * @return void
     */
    protected function addTabView(array &$form, string|array $title, array $data): void
    {
        $store = [];
        if ($data)
            foreach($data as $key => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = Dumper::dumpAsString($value);
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

        $stickItems = $stick->geItemsByPriority(Logger::INFO, 'server');
        if (empty($stickItems))
            throw new Exception\NotDefinedException(
                $this->t('There is no information about the selected stick')
        );

        $tabItems = [];
        foreach ($stickItems as $tab) {
            $this->addTabView($tabItems, $tab['message'], $tab['extra']);
        }

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->router->id= null;
        $window->form->router->route = Gm::alias('@match', '/form');
        $window->form->autoScroll = true;
        $window->form->loadJSONFile('/server-form', 'items', [
            '@items' => $tabItems
        ]);

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->ui = 'light';
        $window->width = 650;
        $window->height = 500;
        $window->layout = 'fit';
        $window->icon = $window->imageSrc('/icon-server.svg');
        $window->title = $this->t('{server.titleTpl}', [$this->getStickModel()->getIdentifier()]);
        $window->maximizable = true;
        $window->addCss('/toolbar.css');
        $this->setViewToolbar($window);
        return $window;
    }
}
