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
use Gm\Exception;
use Gm\Panel\Data\Model\FormModel;

/**
 * Модель данных информации о стике.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Model
 * @since 1.0
 */
class StickInfoModel extends FormModel
{
    /**
     * Стики с их атрибутами.
     *
     * @var null|array
     */
    protected ?array $sticks = null;

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): mixed
    {
        return Gm::$app->router->get('id');
    }

    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'ipaddress'     => 'ipaddress',
            'date'          => 'time',
            'stick'         => 'stick',
            'moduleId'      => 'module',
            'controller'    => 'controller',
            'action'        => 'action',
            'queryId'       => 'queryId',
            'route'         => 'route',
            'method'        => 'method',
            'osName'        => 'osName',
            'osFamily'      => 'osFamily',
            'browserName'   => 'browserName',
            'browserFamily' => 'browserFamily',
            'ajax'          => 'ajax',
            'statusCode'    => 'statusCode'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function get(mixed $identifier = null): ?static
    {
        if ($identifier === null) {
            $identifier = $this->getIdentifier();
        }
        return $identifier ? $this->selectStickInfo($identifier) : null;
    }

    /**
     * Чтения информации стика с идентификатором $stickId.
     * 
     * @param string $stickId Идентификатор стика.
     * 
     * @return array Информация о стике.
     * 
     * @throws Exception\NotDefinedException
     */
    public function readStickInfo(string $stickId): ?array
    {
        if ($this->sticks === null) {
            $writer = Gm::$app->logger->getWriter('debug');
            if (!file_exists($writer->getIndexFilename()))
                throw new Exception\NotDefinedException(
                    $this->t('The main debug file "{0}" is not found, specify the correct path', [$writer->getIndexFilename()])
                );
            $this->sticks = $writer->getIndexSticks();
        }
        return isset($this->sticks[$stickId]) ? $this->sticks[$stickId] : null;
    }

    /**
     * Выборка стика с назначением атрибутам модели данных.
     * 
     * @param string $identifier Идентификатор стика.
     * 
     * @return StickInfoModel
     * 
     * @throws Exception\NotDefinedException
     */
    public function selectStickInfo(string $identifier): StickInfoModel
    {
        $row = $this->readStickInfo($identifier);
        if ($row) {
            $this->reset();
            $this->afterSelect();
            $this->populate($this, $row);
            $this->afterPopulate();
            return $this;
        }
        throw new Exception\NotDefinedException(
            $this->module->t('Unable to retrieve stick information with id {0}, it may have been deleted', [$identifier])
        );
    }
}
