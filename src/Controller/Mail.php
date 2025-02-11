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

/**
 * Контроллер панели запросов.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Mail extends Panel
{
    /**
     * {@inheritdoc}
     */
    public function createWidget(): InfoWindow
    {
        /** @var StickModel|null $stick */
        $stick = $this->getStickModel()->get();
        if (empty($stick)) {
            throw new Exception\NotDefinedException($this->t('There is no information about the selected stick'));
        }

        $stickItems = $stick->geItemsByPriority(Logger::DEBUG, 'mail');
        if (empty($stickItems )|| empty($stick->mail)) {
            throw new Exception\NotDefinedException($this->t('No info for debug panel'));
        }

        /** @var InfoWindow $window */
        $window = parent::createWidget();

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->router->id = null;
        $window->form->router->route = Gm::alias('@match', '/form');
        $window->form->autoScroll = true;
        $window->form->bodyPadding = 0;
        $window->form->loadJSONFile('/mail-form', 'items', [
            '@storeData' => $stick->mailExport()
        ]);

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->ui = 'light';
        $window->width = '99%';
        $window->height = 400;
        $window->layout = 'fit';
        $window->icon = $window->imageSrc('/icon-mail.svg');
        $window->title = $this->t('{mail.titleTpl}', [$stick->getIdentifier()]);
        $window->maximizable = true;
        $window->addCss('/toolbar.css');
        $this->setViewToolbar($window);
        return $window;
    }
}
