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
use Gm\Helper\Html;
use Gm\Panel\Http\Response;
use Gm\Panel\Helper\ExtGrid;
use Gm\Panel\Helper\HtmlGrid;
use Gm\Panel\Widget\TabGrid;
use Gm\Panel\Helper\HtmlNavigator as HtmlNav;
use Gm\Panel\Controller\GridController;

/**
 * Контроллер списка стиков.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Controller
 * @since 1.0
 */
class Grid extends GridController
{
    /**
     * {@inheritdoc}
     */
    protected string $defaultModel = 'StickGrid';

    /**
     * {@inheritdoc}
     */
    public function createWidget(): TabGrid
    {
        $isActive = Gm::useDebugLogging();
        /** @var \Gm\Backend\DebugToolbar\Helper\FilterHelper Фильтр панели инструментов */
        $helper = $this->module->getHelper('FilterHelper');

        /** @var \Gm\Panel\Widget\TabGrid $tab Сетка данных (Gm.view.grid.Grid GmJS) */
        $tab = parent::createWidget();

        // столбцы таблицы
        $tab->grid->columns = [
            ExtGrid::columnNumberer(),
            ExtGrid::columnAction(),
            [
                'xtype'     => 'templatecolumn',
                'text'      => '#Date',
                'align'     => 'center',
                'tooltip'   => '#Date added to journal',
                'dataIndex' => 'date',
                'format'    => 'd/m/Y H:i:s',
                'tpl'       => '{date}',
                'filter'    => ['type' => 'date', 'dateFormat' => 'Y-m-d'],
                'width'     => 140
            ],
            [
                'text'      => '#Ip address',
                'dataIndex' => 'ipaddress',
                'filter'    => ['type' => 'string'],
                'sortable'  => false,
                'width'     => 90
            ],
            [
                'text'    => ExtGrid::columnInfoIcon($this->t('ID Stick')),
                'cellTip' => HtmlGrid::tags([
                      HtmlGrid::header('{stick}'),
                      HtmlGrid::fieldLabel($this->t('Date'), '{date}'),
                      HtmlGrid::fieldLabel($this->t('Ip address'), '{ipaddress}'),
                      ['fieldset',
                          [
                              HtmlGrid::legend($this->t('Module')),
                              HtmlGrid::fieldLabel($this->t('ID'), '{moduleId}'),
                              HtmlGrid::fieldLabel($this->t('Name'), '{moduleName}'),
                          ]
                      ],
                      ['fieldset',
                          [
                              HtmlGrid::legend($this->t('Controller')),
                              HtmlGrid::fieldLabel($this->t('Action'), '{action}'),
                              HtmlGrid::fieldLabel($this->t('ID'), '{queryId}'),
                          ]
                      ],
                      ['fieldset',
                          [
                              HtmlGrid::legend($this->t('Request')),
                              HtmlGrid::fieldLabel($this->t('OS'), '{os}'),
                              HtmlGrid::fieldLabel($this->t('Browser'), '{browser}'),
                              HtmlGrid::fieldLabel($this->t('Route'), '{route}'),
                              HtmlGrid::fieldLabel($this->t('Method'), '{method}'),
                              HtmlGrid::fieldLabel(
                                $this->t('AJAX request'),
                                HtmlGrid::tplChecked('ajax')
                            ),
                              HtmlGrid::fieldLabel($this->t('Status'), '{statusCode}'),
                          ]
                      ]
                  ]),
                  'dataIndex' => 'stick',
                  'xtype'     => 'templatecolumn',
                  'tpl'       => HtmlGrid::tpl(['<span class="g-stick-notexist">{stick}</span>', '<tpl else>', '{stick}'], ['if' => 'stickNotExist']),
                  'filter'    => ['type' => 'string'],
                  'width'     => 110
            ],
            [
                'text'      => '#Memory',
                'dataIndex' => 'memory',
                'filter'    => ['type' => 'string'],
                'width'     => 110
            ],
            [
                'text'      => '#Module',
                'sortable'  => false,
                'columns'   => [
                    [
                        'text'      => 'ID',
                        'dataIndex' => 'moduleId',
                        'cellTip'   => '{moduleId}',
                        'filter'    => ['type' => 'string'],
                        'width'     => 160
                    ],
                    [
                        'text'    => ExtGrid::columnInfoIcon($this->t('Name')),
                        'cellTip' => HtmlGrid::tags([
                            HtmlGrid::header('{moduleName}'),
                            HtmlGrid::fieldLabel($this->t('ID'), '{moduleId}')
                        ]),
                        'dataIndex' => 'moduleName',
                        'filter'    => ['type' => 'string'],
                        'width'     => 160
                    ]
                ]
            ],
            [
                'text'      => '#Controller',
                'sortable'  => false,
                'columns'   => [
                    [
                        'text'      =>  ExtGrid::columnInfoIcon($this->t('Name')),
                        'cellTip' => HtmlGrid::tags([
                                HtmlGrid::header('{controller}'),
                                HtmlGrid::fieldLabel($this->t('Action'), '{action}'),
                                HtmlGrid::fieldLabel('ID', '{queryId}')
                        ]),
                        'dataIndex' => 'controller',
                        'filter'    => ['type' => 'string'],
                        'width'     => 110
                    ],
                    [
                        'text'      => '#Action',
                        'dataIndex' => 'action',
                        'filter'    => ['type' => 'string'],
                        'width'     => 90
                    ],
                    [
                        'text'      => 'ID',
                        'dataIndex' => 'queryId',
                        'filter'    => ['type' => 'string'],
                        'width'     => 70
                    ]
                ]
            ],
            [
                'text'      => '#Request',
                'sortable'  => false,
                'columns'   => [
                    [
                        'xtype'     => 'templatecolumn',
                        'text'      => '#OS',
                        'dataIndex' => 'osName',
                        'tooltip'   => '#Operating System',
                        'cellTip'   => '{osName}',
                        'tpl'       => Html::tag('img', '', ['align' => 'absmiddle', 'src' => Gm::alias('@theme::') .'/assets/icons/png/os/{osLogo}.png']) . ' {osName}',
                        'filter'    => ['type' => 'string'],
                        'width'     => 100
                    ],
                    [
                        'xtype'     => 'templatecolumn',
                        'text'      => '#Browser',
                        'dataIndex' => 'browserName',
                        'cellTip'   => '{browserName}',
                        'tpl'       => Html::tag('img', '', ['align' => 'absmiddle', 'src' => Gm::alias('@theme::') .'/assets/icons/png/browser/{browserLogo}.png']) . ' {browserName}',
                        'filter'    => ['type' => 'string'],
                        'width'     => 100
                    ],
                    [
                        'text'      => '#Route',
                        'dataIndex' => 'route',
                        'cellTip'   => '{route}',
                        'filter'    => ['type' => 'string'],
                        'width'     => 180
                    ],
                    [
                        'text'      => '#Method',
                        'dataIndex' => 'method',
                        'tooltip'   => '#Request method',
                        'filter'    => ['type' => 'string'],
                        'width'     => 70
                    ],
                    [
                        'text'      => 'AJAX',
                        'xtype'     => 'templatecolumn',
                        'dataIndex' => 'ajax',
                        'tooltip'   => '#AJAX request',
                        'tpl'       => ExtGrid::renderIcon('g-icon_size_14 g-icon-m_circle g-icon-m_color_{ajax}', 'svg'),
                        'filter'    => ['type' => 'boolean'],
                        'align'     => 'center',
                        'width'     => 60
                    ],
                    [
                        'text'      => '#Status',
                        'dataIndex' => 'statusCode',
                        'tooltip'   => '#Status code',
                        'filter'    => ['type' => 'string'],
                        'width'     => 70
                    ]
                ]
            ]
        ];

        // панель инструментов (Gm.view.grid.Grid.tbar GmJS)
        $tab->grid->tbar = [
            'padding' => 1,
            'items'   => ExtGrid::buttonGroups([
                'edit'    => 'cleanup,refresh',
                'columns' => 'profiling,columns',
                // группа инструментов "Поиск"
                'search'  => [
                    'items' => [
                        'help',
                        'search',
                        // инструмент "Фильтр"
                        'filter' => ExtGrid::popupFilter([
                            [
                                'xtype'    => 'fieldset',
                                'title'    => '#Request',
                                'defaults' => ['labelWidth' => 80],
                                'items'    => [
                                    $helper->comboBoxOs(),
                                    $helper->comboBoxBrowsers(),
                                    $helper->comboBoxMethods()
                                ]
                            ]
                        ])
                    ]
                ],
                'custom' => ExtGrid::buttonGroup([], [
                    'title' => '#Additionally',
                    'items' => [
                         ExtGrid::button([
                            'text'        => $isActive ? '#Active' : '#Inactive',
                            'tooltip'     => '#Writing user actions to the debugger toolbar - ' . ($isActive ? 'active' : 'inactive'),
                            'iconCls'     => 'g-icon-svg gm-debugtoolbar__icon-debug-' . ($isActive ? 'active' : 'inactive'),
                            'handler'     => 'loadWidget',
                            'handlerArgs' => ['route' => '@backend/config/logger']
                        ]),
                        ExtGrid::button([
                            'xtype'       => 'g-gridbutton-split',
                            'text'        => '#State',
                            'tooltip'     => '#State',
                            'iconCls'     => 'g-icon-svg gm-debugtoolbar__icon-state',
                            'handler'     => 'loadWidget',
                            'handlerArgs' => ['route' => Gm::alias('@match', '/session')],
                            'menu'    => [
                                'mouseLeaveDelay' => 0,
                                'items' => [
                                    [
                                        'text'        => '#Session panel',
                                        'handler'     => 'loadWidget',
                                        'handlerArgs' => ['route' => Gm::alias('@match', '/session')],
                                        'icon'        => $tab->imageSrc('/icon-session.svg')
                                    ]
                                ]
                            ]
                        ]),
                        ExtGrid::button([
                            'xtype'       => 'g-gridbutton-split',
                            'text'        => '#Configure',
                            'tooltip'     => '#Configure',
                            'iconCls'     => 'g-icon-svg gm-debugtoolbar__icon-server-info',
                            'handler'     => 'loadWidget',
                            'handlerArgs' => ['route' => Gm::alias('@match', '/preprocessor')],
                            'menu'    => [
                                'mouseLeaveDelay' => 0,
                                'items' => [
                                    [
                                        'text'        => '#Preprocessor panel',
                                        'handler'     => 'loadWidget',
                                        'handlerArgs' => ['route' => Gm::alias('@match', '/preprocessor')],
                                        'iconCls'     => 'g-icon-svg gm-debugtoolbar__icon-server-info_small'
                                    ],
                                    [
                                        'text'        => '#Web panel',
                                        'handler'     => 'loadWidget',
                                        'handlerArgs' => ['route' => Gm::alias('@match', '/web')],
                                        'iconCls'     => 'g-icon-svg gm-debugtoolbar__icon-server-info_small'
                                    ]
                                ]
                            ]
                        ]),
                        ExtGrid::button([
                            'xtype'       => 'g-gridbutton',
                            'text'        => '#Command',
                            'tooltip'     => '#Executing commands',
                            'iconCls'     => 'g-icon-svg gm-debugtoolbar__icon-cmd',
                            'handler'     => 'loadWidget',
                            'handlerArgs' => ['route' => Gm::alias('@match', '/command')]
                        ])
                    ]
                ])
            ])
        ];

        // контекстное меню записи (Gm.view.grid.Grid.popupMenu GmJS)
        $items = [];
        $toolbar = $this->module->getConfigParam('toolbar');
        if ($toolbar) {
            $first = true;
            foreach ($toolbar as $name => $item) {
                $items[] = [
                    'text' => $item['text'],
                    'icon' => $tab->imageSrc('/icon-' . $name .'.svg'),
                    'handlerArgs' => [
                        'route'   => Gm::alias('@match', '/' . $name . '/view/{id}'),
                        'pattern' => 'grid.popupMenu.activeRecord'
                    ],
                    'handler' => 'loadWidget'
                ];
                if ($first) {
                    $items[] = '-';
                    $first = false;
                }
            }
        }
        $tab->grid->popupMenu = ['items' => $items];

        // 2-й клик по строке сетки
        $tab->grid->rowDblClickConfig = [
            'allow' => true,
            'route' => Gm::alias('@match', '/stick/view/{id}')
        ];
        // количество записей в таблице
        $tab->grid->store->pageSize = 100;
        // плагины сетки
        $tab->grid->plugins = 'gridfilters';
        // класс CSS применяемый к элементу body сетки
        $tab->grid->bodyCls = 'g-grid_background'; 

        // панель навигации (Gm.view.navigator.Info GmJS)
        $tab->navigator->info['tpl'] = HtmlNav::tags([
            HtmlNav::header('{stick}'),
            ['fieldset',
                [
                    HtmlNav::fieldLabel($this->t('Date'), '{date}'),
                    HtmlNav::fieldLabel($this->t('IP address'), '{ipaddress}'),
                    HtmlNav::fieldLabel($this->t('Stick'), '{stick}'),
                ]
            ],
            ['fieldset',
                [
                    HtmlNav::legend($this->t('Module')),
                    HtmlNav::fieldLabel($this->t('ID'), '{moduleId}'),
                    HtmlNav::fieldLabel($this->t('Name'), '{moduleName}'),
                ]
            ],
            ['fieldset',
                [
                    HtmlNav::legend($this->t('Controller')),
                    HtmlNav::fieldLabel($this->t('Name'), '{controller}'),
                    HtmlNav::fieldLabel($this->t('Action'), '{action}'),
                    HtmlNav::fieldLabel($this->t('ID'), '{queryId}'),
                ]
            ],
            ['fieldset',
                [
                    HtmlNav::legend($this->t('Request')),
                    HtmlNav::fieldLabel($this->t('OS'), '{osName}'),
                    HtmlNav::fieldLabel($this->t('Browser'), '{browserName}'),
                    HtmlNav::fieldLabel($this->t('Route'), '{route}'),
                    HtmlNav::fieldLabel($this->t('Method'), '{method}'),
                    HtmlNav::fieldLabel(
                        $this->t('AJAX'), 
                        HtmlNav::tplChecked('ajax')
                    ),
                    HtmlNav::fieldLabel($this->t('Status'), '{statusCode}'),
                    
                ]
            ]
        ]);

        $tab->addCss('/toolbar.css');
        return $tab;
    }

    /**
     * {@inheritdoc}
     */
    public function clearAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var \Gm\Panel\Data\Model\ArrayGridModel $model */
        $model = $this->getModel($this->defaultModel);
        if ($model === false) {
            $response
                ->meta->error(Gm::t('app', 'Could not defined data model "{0}"', [$this->defaultModel]));
            return $response;
        }

        if ($this->useAppEvents) {
            Gm::$app->doEvent($this->makeAppEventName(), [$this, $model]);
        }

        // удаление записей
        if ($model->clear() === false) {
            // если не было сообщения об ошибке ранее
            if (!$response->meta->isError()) {
                $response
                    ->meta->error(Gm::t(BACKEND, 'Could not delete record'));
                return $response;
            }
        }

        if ($this->useAppEvents) {
            Gm::$app->doEvent($this->makeAppEventName('After'), [$this, $model]);
        }
        return $response;
    }
}
