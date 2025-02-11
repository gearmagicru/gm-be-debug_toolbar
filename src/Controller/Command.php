<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\DebugToolbar\Controller;

use Gm\Panel\Widget\InfoWindow;

/**
 * Контроллер панели конфигурации HTTP сервера.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Command extends Panel
{
    /**
     * {@inheritdoc}
     */
    public function createWidget(): InfoWindow
    {
        /** @var InfoWindow $window */
        $window = parent::createWidget();

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->autoScroll = true;
        $window->form->items = [
            [
                'xtype'      => 'textfield',
                'hideLabel'  => true,
                'labelAlign' => 'right',
                'labelWidth' => 100,
                'anchor'     => '100%',
                'value'      => 'filemanager/dialog/browse type=image path=@media applyTo=me',
                'listeners'  => [
                    'keyup' => 'keyRun'
                ],
                'triggers'   => [
                    'run' => [
                        'cls'     => $this->module->viewId('cmd'),
                        'handler' => 'onRun'
                    ]
                ],
                'enableKeyEvents' => true
            ]
        ];
        $window->form->controller = 'gm-be-debug_toolbar-command';
        $window->form->bodyPadding = '0 5 3 5';
        $window->form->router->state = 'custom';

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->ui = 'light';
        $window->positionAlign = 'right bottom';
        $window->padding = 0;
        $window->width = 400;
        $window->autoHeight = true;
        $window->layout = 'fit';
        $window->iconCls = 'g-icon-svg gm-debugtoolbar__icon-cmd_small';
        $window->title = $this->t('{command.title}');
        $window->maximizable = false;
        $window->modal = false;
        $window
            ->setNamespaceJS('Gm.be.debug_toolbar')
            ->addRequire('Gm.be.debug_toolbar.CommandController')
            ->addCss('/toolbar.css');
        return $window;
    }
}
