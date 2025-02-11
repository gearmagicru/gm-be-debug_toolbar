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
 * Контроллер панели конфигурации «PHP: препроцессор гипертекста».
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Preprocessor extends Panel
{
    /**
     * Возвращает информацию о PHP.
     * 
     * @return string
     */
    public function getViewInfo(): string
    {
        ob_start();
        phpinfo();
        $content = ob_get_contents();
        ob_end_clean();
        $content = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $content);
        return "
            <style type='text/css'>
                #phpinfo { font: normal 13px/15px \"Roboto Condensed\", helvetica, arial, verdana, sans-serif; margin: 4px; }
                #phpinfo .h td a { display: none }
                #phpinfo pre {margin: 0; font-family: monospace;}
                #phpinfo a:link {color: #009; text-decoration: none; background-color: #fff;}
                #phpinfo a:hover {text-decoration: underline;}
                #phpinfo table { border-collapse: collapse; border: 0; width: 100%; box-shadow: none; font: normal 16px/15px \"Roboto Condensed\", helvetica, arial, verdana, sans-serif }
                #phpinfo .center {text-align: center;}
                #phpinfo .center table {margin: 1em auto; text-align: left;}
                #phpinfo .center th {text-align: center !important;}
                #phpinfo td, th {border: 1px solid #c1c1c1; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
                #phpinfo h1 {font-size: 150%;}
                #phpinfo h2 {font-size: 125%;}
                #phpinfo .p {text-align: left;}
                #phpinfo .e {background-color: #F5F5F5; width: 300px; font-weight: bold; color: #666; }
                #phpinfo .h { background-color: #217346; font-weight: bold; color: #fff; }
                #phpinfo .h th { border: 0 }
                #phpinfo .v {background-color: #fefefe; max-width: 300px; overflow-x: auto; word-wrap: break-word;}
                #phpinfo .v i {color: #999;}
                #phpinfo img {float: right; border: 0;}
                #phpinfo hr {width: 100%; background-color: #ccc; border: 0; height: 1px;}
            </style>
            <div id='phpinfo'>$content</div>";
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
        $window->form->html = $this->getViewInfo();

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->ui = 'light';
        $window->width = 800;
        $window->height = 500;
        $window->layout = 'fit';
        $window->iconCls = 'g-icon-svg gm-debugtoolbar__icon-server-info_small';
        $window->title = $this->t('{preprocessor.titleTpl}');
        $window->maximizable = true;
        return $window;
    }
}
