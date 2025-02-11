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
use Gm\Panel\Data\Model\FormModel;
use Gm\Exception;
use Gm\Debug\Dumper;

/**
 * Модель данных стика.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Model
 * @since 1.0
 */
class StickModel extends FormModel
{
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
    public function get(mixed $identifier = null): ?static
    {
        if ($identifier === null) {
            $identifier = $this->getIdentifier();
        }
        return $identifier ? $this->selectStick($identifier) : null;
    }

    /**
     * Возвращает элементы для категории по указанному приоритету.
     * 
     * @param int $priority Приоритет службы Логирования {@see \Gm\Log\Logger} (EMERGENCY, ALERT, CRITICAL, 
     * ERROR, WARNING, NOTICE, INFO, DEBUG, PROFILING).
     */
    public function geItemsByPriority(int $priority, string $category = null)
    {
        $result = $attributes = [];
        if ($category === null)
            $attributes = array_keys($this->attributes);
        else
        if (is_string($category))
            $attributes = [$category];
        else
            $attributes = $category;
        foreach($attributes as $name) {
            $items = $this->$name;
            if ($items)
                foreach($items as $item) {
                    if ($item['priority'] == $priority)
                        $result[] = $item;
                }
        }
        return $result;
    }

    /**
     * Чтения стика из файла с идентификатором $stickId.
     * 
     * @param string $stickId Идентификатор стика.
     * 
     * @return array Данные стика.
     * 
     * @throws Exception\NotDefinedException
     */
    public function readStick(string $stickId): array
    {
        $writer = Gm::$app->logger->getWriter('debug');

        $filename = $writer->getStickFilename($stickId);
        if (!file_exists($filename))
            throw new Exception\NotDefinedException(
                $this->module->t('No stick {0} file found', [$filename])
            );
        $stick = $writer->loadStickFile($filename);
        if (empty($stick))
            throw new Exception\NotDefinedException(
                $this->t('There is no information about the selected stick')
            );
        return $stick;
    }

    /**
     * Выборка стика с назначением атрибутам модели данных.
     * 
     * @param string $identifier Идентификатор стика.
     * 
     * @return null|StickModel Если null, стик не существует или его невозможно прочитать.
     */
    public function selectStick(string $identifier): ?StickModel
    {
        $row = $this->readStick($identifier);
        if ($row) {
            $this->reset();
            $this->afterSelect();
            $this->populate($this, $row);
            $this->afterPopulate();
            return $this;
        } else
            return null;
    }

    /**
     * Экспорт отладочной информации.
     * 
     * @return array
     */
    public function debugExport(): array
    {
        
        $rows = [];
        if ($this->debug) {
            foreach($this->debug as $var) {
                $name  = $var['message'];
                $value = $var['extra'];
                $type  = gettype($value);
                if (is_string($value))
                    $size = strlen($value);
                else
                if (is_countable($value) || is_array($value))
                    $size = sizeof($value);
                else
                    $size = 1;
                $value = Dumper::dumpAsString($value);
                $rows[] = [
                    'name'  => $name, // название
                    'type'  => $type, // тип значения
                    'size'  => $size, // размер значения
                    'value' => $value // значение 
                ];
            }
        }
        return $rows;
    }

    /**
     * Экспорт отладочной информации письма.
     * 
     * @return array
     */
    public function mailExport(): array
    {
        $rows = [];
        if ($this->mail) {
            foreach($this->mail as $var) {
                $value = $var['extra'];
                $service = isset($value['service']) ? Dumper::dumpAsString($value['service']) : '';
                $rows[] = [
                    'message'    => $var['message'], // описание 
                    'error'      => $value['error'] ?? '', // ошибка
                    'level'      => $value['level'] ?? '', // уровень отладки
                    'to'         => $value['to'] ?? '', // адрес "кому"
                    'replyTo'    => $value['replyTo'] ?? '', // адрес "Reply-To"
                    'bcc'        => $value['bcc'] ?? '', // адрес "Bcc"
                    'cc'         => $value['cc'] ?? '', // адрес "Cc"
                    'from'       => $value['from'] ?? '', // адрес "от кого"
                    'body'       => $value['body'] ?? '', // текст письма
                    'subject'    => $value['subject'] ?? '', // тема
                    'headers'    => $value['headers'] ?? '', // заголовки письма
                    'messageId'  => $value['messageId'] ?? '', // ID сообщения
                    'debugSteps' => $value['debugSteps'] ?? '', // шаги сборки письма
                    'service'    => $service // параметры конфигурации службы почты {@see \Gm\Mail\Mail}
                ];
            }
        }
        return $rows;
    }
}
