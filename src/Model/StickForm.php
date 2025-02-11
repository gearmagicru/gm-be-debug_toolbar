<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Backend\DebugToolbar\Model;

use Gm;
use Gm\Helper\Browser;

/**
 * Модель данных информации о стике.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Model
 * @since 1.0
 */
class StickForm extends StickInfoModel
{
    /**
     * {@inheritdoc}
     */
    public function processing(): void
    {
        parent::processing();

        // дата стика
        if ($this->date)
            $this->date = Gm::$app->formatter->toDateTime($this->date, 'php:d/m/Y H:i:s');
        // версия ос
        if ($this->osFamily) {
            $this->osName = '<img align="absmiddle" src="' 
                          . Gm::alias('@theme::') .'/assets/icons/png/os/' 
                          . (Browser::$osLogos[$this->osFamily] ?? 'none')
                          . '.png"> ' 
                          . $this->osName;
        }
        // версия браузера
        if ($this->browserFamily) {
            $this->browserName = '<img align="absmiddle" src="' 
                               . Gm::alias('@theme::') .'/assets/icons/png/browser/' 
                               . (Browser::$browserLogos[$this->browserFamily] ?? 'none')
                               . '.png"> ' 
                               . $this->browserName;
        }
        // имя модуля
        if ($this->moduleId) {
            $module = Gm::$app->modules->getRegistry()->get($this->moduleId);
            if ($module) {
                $this->moduleName = $module['name'];
            }
        }
        $this->ajax = isset($this->ajax) ? ((int) $this->ajax ? $this->t('Yes') : $this->t('No')) : $this->t('No');
    }
}
