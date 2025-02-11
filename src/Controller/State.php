<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\DebugToolbar\Controller;

use Gm\Helper\Str;
use Gm\Panel\Widget\InfoWindow;

/**
 * Контроллер состояния панель инструментов отладчика.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class State extends Panel
{
    /**
     * {@inheritDoc}
     */
    protected string $defaultModel = 'DebugForm';

    /**
     * {@inheritdoc}
     */
    public function createWidget(): InfoWindow
    {
        /** @var InfoWindow $window */
        $window = parent::createWidget();

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->id = null;
        $window->form->autoScroll = true;
        $window->form->loadJSONFile('/state-form', 'items', [
            '{appDebug}'                  => Str::boolToStr(GM_DEBUG),
            '{appDebugCleanOb}'           => Str::boolToStr(false),
            '{appEnableErrorHandler}'     => Str::boolToStr(GM_ENABLE_ERROR_HANDLER),
            '{appEnableExceptionHandler}' => Str::boolToStr(GM_ENABLE_EXCEPTION_HANDLER),
            '{appAutoloadTrace}'          => Str::boolToStr(false),
        ]);


        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->ui = 'light';
        $window->width = 500;
        $window->height = 500;
        $window->bodyPadding = 4;
        $window->layout = 'fit';
        $window->icon = $window->imageSrc('/icon-server.svg');
        $window->title = $this->t('{web.titleTpl}');
        $window->maximizable = true;
        $window
            ->addCss('/toolbar.css')
            ->addCss('/debug.css');
        return $window;
    }

}
