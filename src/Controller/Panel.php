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
use Gm\Panel\Helper\ExtForm;
use Gm\Panel\Widget\InfoWindow;
use Gm\Panel\Controller\InfoController;
use Gm\Backend\DebugToolbar\Model\StickModel;

/**
 * Контроллер панели.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Panel extends InfoController
{
    /**
     *  Модель данных стика.
     *
     * @var StickModel
     */
    protected StickModel $stickModel;

    /**
     * Возвращает модель данных стика.
     * 
     * @return StickModel
     */
    protected function getStickModel(): StickModel
    {
        if (!isset($this->stickModel)) {
            $this->stickModel = $this->getModel('StickModel');
        }
        return $this->stickModel;
    }

    /**
     * @param InfoWindow $window
     * 
     * @return array
     */
    public function setViewToolbar(InfoWindow $window): array
    {
        $stickId = $this->getStickModel()->getIdentifier();
        $init    = $this->module->getConfigParam('toolbar');
        $toolbar = [];
        if ($init) {
            $window->form->router->state = 'custom';
            $window->form->buttonAlign = 'right';
            $buttons = [ExtForm::closeButton(['text' => '#Close'])];
            $controller = $this->getName();
            foreach ($init as $name => $item) {
                if ($name === $controller) continue;
                $buttons[] = 
                    ExtForm::toolButton([
                        'cls' => 'gm-debugtoolbar__btn',
                        'icon'        => $window->imageSrc('/icon-' . $name .'.svg'),
                        'tooltip'     => $item['text'],
                        'handlerArgs' => [
                            'route'   => Gm::alias('@match', "/$name/view/$stickId")
                        ]
                    ]);
            }
            $window->form->buttons = $buttons;
        }
        return $toolbar;
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): InfoWindow
    {
        /** @var InfoWindow $window */
        $window = parent::createWidget();
        $window->iconCls = '';

        // закрывать предыдущие окна оладки если они открыты
        $this->getResponse()
            ->meta
                ->cmdComponent($this->module->viewId('window'), 'close');
        return $window;
    }
}
