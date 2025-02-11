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
use Gm\I18n\Formatter;
use Gm\Helper\Browser;
use Gm\Log\Writer\DebugWriter;
use Gm\Panel\Data\Model\ArrayGridModel;

/**
 * Модель данных списка стиков.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Model
 * @since 1.0
 */
class StickGrid extends ArrayGridModel
{
    /**
     * {@inheritdoc}
     */
    public bool $useDirectFilterOnFetch = true;

    /**
     * Писатель отладочной информации в журнал.
     * 
     * @var DebugWriter
     */
    protected DebugWriter $writer;

    /**
     * Форматтер.
     * 
     * @var Formatter
     */
    protected Formatter $formatter;

    /**
     * Установленные модули.
     * 
     * Для определения имен используемых модулей.
     * 
     * @see StickGrid::beforeFetchRows()
     *
     * @var array
     */
    protected array $modules;

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'fields' => [
                ['date'],
                ['ipaddress'],
                ['stick'],
                ['id'],
                ['amountMemory'],
                ['peakMemory'],
                ['memory'],
                ['moduleId'],
                ['moduleRowId'],
                ['moduleName'],
                ['controller'],
                ['action'],
                ['queryId'],
                ['route'],
                ['method'],
                ['statusCode'],
                ['statusCategory'],
                ['osName'],
                ['osFamily'],
                ['osLogo'],
                ['browserName'],
                ['browserFamily'],
                ['browserLogo'],
                ['ajax'],
                ['stickNotExist']
            ],
            'filter' => [
                'browserFamily' => ['operator' => '='],
                'osFamily'      => ['operator' => '='],
                'method'        => ['operator' => '='],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this->writer = Gm::$app->logger->getWriter('debug');
        $this
            ->on(self::EVENT_AFTER_DELETE, function ($someRecords, $result, $message) {
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            })
            ->on(self::EVENT_AFTER_SET_FILTER, function ($filter) {
                /** @var \Gm\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * {@inheritdoc}
     */
    public function clearRows(): false|int
    {
        $count = $this->writer->getCountSticks();

        /** @var true|array $result */
        $result = $this->writer->removeStickFiles();
        // удаление тега файлов
        if ($result !== true) {
            // для отладки
            Gm::debug('removeStickFiles', [
                'Error' => $this->module->t('Unable to delete tag file: {0}', [implode('<br>', $result)])
            ]);
            return false;
        }

        /** @var true|array $result */
        $result = $this->writer->removeIndexFile();
        // удаление индексного файла
        if ($result !== true) {
            // для отладки
            Gm::debug('removeIndexFile', [
                'Error' => $this->module->t('Unable to delete index file: {0}', [$result])
            ]);
            return false;
        }
        return $count;
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue(array $filter, mixed $value, array $row): ?bool
    {
        static $dateEq, $dateGt;

        //фильтрация по полю "AJAX"
        if ($filter['property'] === 'ajax') {
            if ($value === 'success') 
                return $filter['value'] === true;
            else
                return $filter['value'] === false;
        }

        //фильтрация по полю "Дата"
        if ($filter['property'] === 'date') {
            /** @var int|null $value  */
            $date = $row['time'];
            if (empty($date)) return false;

            if ($dateEq === null) {
                // текущая дата
                $dateEq = strtotime($filter['value']);
                // дата после
                $dateGt = strtotime($filter['value'] . ' + 1 day');
            }

            $operator = $filter['operator'];
            // на указанную дату
            if ($operator === 'eq') return $date >= $dateEq && $date <= $dateGt;
            // до указанной даты
            if ($operator === 'lt') return $date < $dateEq;

            // после указанной даты
            if ($operator === 'gt') {
                return $date >= $dateGt;
            }
        }
        return null;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function buildQuery($builder)
    {
        if (file_exists($this->writer->getIndexFilename()))
            // читает стики с их атрибутами из главного файла стиков
            return $this->writer->getIndexSticks();
        else
            return [];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeFetchRows(): void
    {
        $this->modules = Gm::$app->modules->getRegistry()->getListInfo(true, false, 'id', false);
        $this->formatter = Gm::$app->formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeFetchRow(mixed $row, int|string $rowKey): ?array
    {
        $hasAjax = ($row['ajax'] ?? 0) > 0;
        $stickId = $row['stick'] ?? '';

        // если указан идентификатор установленного модуля
        $moduleId = $row['module'] ?? null;
        if ($moduleId) {
            $moduleRowId = $this->modules[$moduleId]['rowId'] ?? null;
            $moduleName  = $this->modules[$moduleId]['name'] ?? null;
        } else {
            $moduleRowId = null;
            $moduleName = null;
        }

        // выделяемая память
        $amountMemory = $row['amountMemory'] ?? 0;
        if ($amountMemory > 0) {
            $amountMemory = $this->formatter->toShortSizeDataUnit($amountMemory);
        }
        $peakMemory = $row['peakMemory'] ?? 0;
        if ($peakMemory > 0) {
            $peakMemory = $this->formatter->toShortSizeDataUnit($peakMemory);
        }

        // значок браузера
        $browserFamily = $row['browserFamily'] ?? '';
        if ($browserFamily)
            $browserLogo = Browser::$browserLogos[$browserFamily] ?? 'none';
        else
            $browserLogo = 'none';

        // значок ОС
        $osFamily = $row['osFamily'] ?? '';
        if ($osFamily)
            $osLogo = Browser::$osLogos[$osFamily] ?? 'none';
        else
            $osLogo = 'none';

        // код состояния
        $statusCode = $row['statusCode'] ?? '';
        $statusCategory = $row['statusCategory'] ?? '';
        if ($statusCode) {
            if ($statusCategory)
                $cls = 'gm-debugtoolbar-grid__cell-status gm-debugtoolbar-grid__cell-status_' . str_replace(' ', '-', $statusCategory);
            else
                $cls = 'gm-debugtoolbar-grid__cell-status gm-debugtoolbar-grid__cell-status_unknow';
            $statusCode = '<span class="' . $cls . '">' . $statusCode . '</span>';
        }
        return [
            'time'           => $row['time'] ?? null,
            'date'           => isset($row['time']) ? $this->formatter->toDateTime($row['time'], 'php:d/m/Y H:i:s') : '',
            'ipaddress'      => $row['ipaddress'],
            'stick'          => $stickId,
            'id'             => $stickId,
            'amountMemory'   => $amountMemory,
            'peakMemory'     => $peakMemory,
            'memory'         => $amountMemory . ' / ' . $peakMemory,
            'moduleId'       => $moduleId,
            'moduleRowId'    => $moduleRowId,
            'moduleName'     => $moduleName,
            'controller'     => $row['controller'] ?? '',
            'action'         => $row['action'] ?? '',
            'queryId'        => $row['queryId'] ?? '',
            'route'          => $row['route'] ?? '',
            'method'         => $row['method'] ?? '',
            'statusCode'     => $statusCode,
            'statusCategory' => $statusCategory,
            'osName'         => $row['osName'] ?? '',
            'osFamily'       => $osFamily,
            'osLogo'         => $osLogo,
            'browserName'    => $row['browserName'] ?? '',
            'browserFamily'  => $browserFamily,
            'browserLogo'    => $browserLogo,
            'ajax'           => $hasAjax ? 'success' : 'inactive',
            'stickNotExist'  => $stickId ? (int) $this->writer->existsStickFile($stickId) : 0
        ];
    }
}
