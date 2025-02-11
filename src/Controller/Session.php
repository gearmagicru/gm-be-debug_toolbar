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
use Gm\Panel\Widget\InfoWindow;

/**
 * Контроллер панели сессии пользователя.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Session extends Panel
{
    /**
     * {@inheritdoc}
     */
    public function createWidget(): InfoWindow
    {
        /** @var InfoWindow $window */
        $window = parent::createWidget();

        /** @var \Gm\Backend\Debug\Toolbar\Model\SessionNodes $nodes */
        $nodes = $this->getModel('SessionNodes');

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->autoScroll = true;
        $window->form->loadJSONFile('/session-form', 'items', [
            '@rootText'     => '<b>' . Gm::$app->session->name . '</b>',
            '@rootChildren' => $nodes->getAllNodes()
        ]);

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->ui = 'light';
        $window->width = 690;
        $window->height = 500;
        $window->layout = 'fit';
        $window->icon = $window->imageSrc('/icon-session.svg');
        $window->title = $this->t('{session.titleTpl}');
        $window->maximizable = true;
        return $window;
    }
}
