<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\DebugToolbar\Controller;

use Gm\Helper\HttpServer;
use Gm\Panel\Widget\InfoWindow;

/**
 * Контроллер панели конфигурации HTTP сервера.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Web extends Panel
{
    /**
     * Возвращает информацию о PHP.
     * 
     * @param InfoWindow $window
     * 
     * @return string
     */
    public function getViewInfo(InfoWindow $window): array
    {
        $info = HttpServer::info();
        $name = $info ? $info['name'] : false;

        $items =[
            [
                'height' => 50,
                'html' => $name ? '<div align="center"><img src="' . $window->imageSrc('/logo/' . $name . '.png') . '"></div>' : ''
            ],
            [
                'xtype'      => 'displayfield',
                'ui'        => 'parameter-head',
                'fieldLabel' => '#Server software',
                'value'      => $info ? $info['fullName'] : '#[unknow]',
                'labelWidth' => 120
            ],
            [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter-head',
                'fieldLabel' => '#Server version',
                'value'      => $info ? HttpServer::version() : '#[unknow]',
                'labelWidth' => 120
            ],
            [
                'html' => '<br>'
            ]
        ];
        if ($info) {
            if (!empty($info['url'])) {
                $items[] = [
                    'xtype'      => 'displayfield',
                    'ui'         => 'parameter',
                    'fieldLabel' => '#Server detail',
                    'value'      => '<a class="g-form__link" target="_blank" href="' . $info['url'] . '">' . $info['url'] . '</a>',
                    'labelWidth' => 120
                ];
            }
            if ($modules = HttpServer::modules()) {
                $items[] = [
                    'xtype'      => 'displayfield',
                    'ui'         => 'parameter',
                    'fieldLabel' => '#Modules',
                    'value'      => implode(', ', $modules),
                    'labelWidth' => 120
                ];
            }
        }
        if (isset($_SERVER['SERVER_NAME'])) {
            $items[] = [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter',
                'fieldLabel' => '#Server name',
                'value'      => $_SERVER['SERVER_NAME'],
                'labelWidth' => 120
            ];
        }
        if (isset($_SERVER['SERVER_PROTOCOL'])) {
            $items[] = [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter',
                'fieldLabel' => '#Protocol',
                'value'      => $_SERVER['SERVER_PROTOCOL'],
                'labelWidth' => 120
            ];
        }
        if (isset($_SERVER['SERVER_PORT'])) {
            $items[] = [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter',
                'fieldLabel' => '#Port',
                'value'      => $_SERVER['SERVER_PORT'],
                'labelWidth' => 120
            ];
        }
        if (isset($_SERVER['SERVER_ADDR'])) {
            $items[] = [
                'xtype'      => 'displayfield',
                'ui'         => 'parameter',
                'fieldLabel' => '#IP address',
                'value'      => $_SERVER['SERVER_ADDR'],
                'labelWidth' => 120
            ];
        }
        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): InfoWindow
    {
        /** @var InfoWindow $window */
        $window = parent::createWidget();

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->autoScroll = true;
        $window->form->items = $this->getViewInfo($window);
        $window->form->bodyPadding = 10;

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->ui = 'light';
        $window->width = 500;
        $window->height = 500;
        $window->layout = 'fit';
        $window->iconCls = 'g-icon-svg gm-debugtoolbar__icon-server-info_small';
        $window->title = $this->t('{web.titleTpl}');
        $window->maximizable = true;
        $window->addCss('/toolbar.css');
        return $window;
    }
}
