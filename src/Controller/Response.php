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
use Gm\Helper\Json;
use Gm\Panel\Widget\InfoWindow;
use Gm\Backend\DebugToolbar\Model\StickModel;

/**
 * Контроллер панели запросов.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Response extends Panel
{
    /**
     * Добавляет вкладки на панель формы компонента.
     * 
     * @param array $form Панель формы.
     * @param string|array $title Заголовок вкладки.
     * @param array $data Параметры сообщения.
     * 
     * @return void
     */
    protected function addTabView(array &$form, string|array $title, array $data): void
    {
        $store = [];
        if ($data)
            foreach($data as $key => $value) {
                if (is_object($value) || is_array($value)) {
                    $value = \Gm\Debug\Dumper::dumpAsString($value);
                }
                $store[] = ['name' => $this->t($key), 'value' => $value];
            }

        $form[] = [
            'title'       => $data ? $this->t($title) . ' <small>(' . sizeof($data) . ')</small>' : $this->t($title),
            'bodyPadding' => 0,
            'layout'      => 'fit',
            'items'       => [
                'xtype'  => 'gridpanel',
                'anchor' => '100% 100%',
                'store'  => [
                    'fields' => ['name', 'value'],
                    'data'   => $store
                ],
                'columns' => [
                    ['text' => '#Name', 'dataIndex' => 'name', 'flex' => 1],
                    ['text' => '#Value', 'dataIndex' => 'value', 'flex' => 2]
                ]
            ]
        ];
    }

    /**
     * Добавляет вкладку с контентом на панель формы компонента.
     * 
     * @param array $form Панель формы.
     * @param string|array $title Заголовок вкладки.
     * @param array $data Параметры сообщения.
     * 
     * @return void
     */
    protected function addTabContentView(array &$form, string|array $title, array $data): void
    {
        if ($data['format'] === 'json') {
            $content = Json::prettyEncode($data['content']);
            if ($error = Json::error())
                $content = $error;
            // вкладка "Content"
            $form[] = [
                'title'       => $this->t($title) . ' ' . strtoupper($data['format']),
                'bodyPadding' => 0,
                'layout'      => 'fit',
                'items'       => [
                    'autoScroll' => true,
                    'anchor'     => '100% 100%',
                    'html'       => '<pre class="gm-debugtoolbar-format_json">' . strip_tags($content) . '</pre>'
                ]
            ];
        } else {
            // вкладка "Content"
            $form[] = [
                'title'       => $this->t($title) . ' ' . strtoupper($data['format']),
                'bodyPadding' => 0,
                'layout'      => 'fit',
                'items'       => [
                    'xtype'  => 'textarea',
                    'anchor' => '100% 100%',
                    'value'  => $data['content']
                ]
            ];
        }
        // вкладка "Raw content"
        if ($data['rawContent']) {
            $form[] = [
                'title'       => $this->t('Raw Content'),
                'bodyPadding' => 0,
                'layout'      => 'fit',
                'items'       => [
                    'autoScroll' => true,
                    'anchor'     => '100% 100%',
                    'html'       => \Gm\Debug\Dumper::dumpAsString($data['rawContent'])
                ]
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createWidget(): InfoWindow
    {
        /** @var InfoWindow $window */
        $window = parent::createWidget();

        /** @var StickModel|null $stick */
        $stick = $this->getStickModel()->get();
        if (empty($stick))
            throw new Exception\NotDefinedException(
                $this->t('There is no information about the selected stick')
        );

        $stickItems = $stick->geItemsByPriority(Logger::INFO, 'response');
        if (empty($stickItems))
            throw new Exception\NotDefinedException(
                $this->t('There is no information about the selected stick')
        );

        // панель вкладок формы
        $tabItems = [];
        foreach ($stickItems as $tab) {
            if ($tab['message'] === 'Content')
                $this->addTabContentView($tabItems, $tab['message'], $tab['extra']);
            else
                $this->addTabView($tabItems, $tab['message'], $tab['extra']);
        }

        // панель формы (Gm.view.form.Panel GmJS)
        $window->form->router->id = null;
        $window->form->router->route = Gm::alias('@match', '/form');
        $window->form->autoScroll = true;
        $window->form->loadJSONFile('/response-form', 'items', [
            '@items' => $tabItems
        ]);

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $window->cls = 'g-window-toolbar';
        $window->ui = 'light';
        $window->width = 690;
        $window->height = 500;
        $window->layout = 'fit';
        $window->iconCls = ''; 
        $window->icon = $window->imageSrc('/icon-response.svg');
        $window->title = $this->t('{response.titleTpl}', [$this->getStickModel()->getIdentifier()]);
        $window->maximizable = true;
        $window->addCss('/toolbar.css');
        $this->setViewToolbar($window);
        return $window;
    }
}
