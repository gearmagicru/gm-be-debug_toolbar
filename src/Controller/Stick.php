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
 * Контроллер формы информации о стике.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Stick extends Panel
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'StickForm';

    /**
     * {@inheritdoc}
     */
    public function createWidget(): InfoWindow
    {
        /** @var InfoWindow $window */
        $window = parent::createWidget();

        // виджет формы (Gm.view.form.Panel GmJS)
        $window->form->autoScroll = true;
        $window->form->router->route = Gm::alias('@match', '/stick');
        $window->form->bodyPadding = 10;
        $window->form->loadJSONFile('/stick-form', 'items');

        // виджет окно (Ext.window.Window Sencha ExtJS)
        $window->ui = 'light';
        $window->width = 580;
        $window->height = 550;
        $window->layout = 'fit';
        $window->icon = $window->imageSrc('/icon-stick.svg');
        $window->title = '#{stick.title}';
        $window->titleTpl = '#{stick.titleTpl}';
        $this->setViewToolbar($window);
        return $window;
    }
}
