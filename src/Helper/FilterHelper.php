<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\DebugToolbar\Helper;

use Gm;
use Gm\Helper\Html;
use Gm\Helper\Browser;
use Gm\Stdlib\BaseObject;
use Gm\Panel\Helper\ExtCombo;

/**
 * Помощник фильтра.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Helper
 * @since 1.0
 */
class FilterHelper extends BaseObject
{
    /**
     * Выпадающий список семейства ОС.
     * 
     * @return array<string, mixed>
     */
    public function comboBoxOs(): array
    {
        $items = [['null', '#none', 'none']];
        foreach(Browser::$osFamilies as $family) {
            $items[] = [$family, $family,  Browser::$osLogos[$family] ?? 'none'];

        }
        return ExtCombo::local(
            '#OS',
            'osFamily',
            [
                'fields' => ['id', 'name', 'logo'],
                'data'   => $items
            ],
            [
                'editable'   => true,
                'listConfig' => [
                    'itemTpl' => Html::div(
                        Html::img(Gm::alias('@theme', '/assets/icons/png/os/{logo}.png'), ['align' => 'absmiddle'], false) . ' {name}',
                        ['data-qtip' => '{name}']
                    )
                ]
            ]
        );
    }

    /**
     * Выпадающий список семейства браузеров.
     * 
     * @return array<string, mixed>
     */
    public function comboBoxBrowsers(): array
    {
        $items = [['null', '#none', 'none']];
        foreach(Browser::$browserFamilies as $family) {
            $items[] = [$family, $family,  Browser::$browserLogos[$family] ?? 'none'];
        }
        return ExtCombo::local(
            '#Browser',
            'browserFamily',
            [
                'fields' => ['id', 'name', 'logo'],
                'data'   => $items
            ],
            [
                'editable'   => true,
                'listConfig' => [
                    'itemTpl' => Html::div(
                        Html::img(Gm::alias('@theme', '/assets/icons/browser/os/{logo}.png'), ['align' => 'absmiddle'], false) . ' {name}',
                        ['data-qtip' => '{name}']
                    )
                ]
            ]
        );
    }

    /**
     * Выпадающий список методов запроса.
     * 
     * @return array<string, mixed>
     */
    public function comboBoxMethods(): array
    {
        $items = [['null', '#none']];
        foreach(Gm::$app->request->allowedMethods as $method) {
            $items[] = [$method, $method];
        }
        return ExtCombo::local(
            '#Method',
            'method',
            [
                'fields' => ['id', 'name'],
                'data'   => $items
            ]
        );
    }
}
