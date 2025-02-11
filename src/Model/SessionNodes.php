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
use Gm\Panel\Data\Model\NodesModel;

/**
 * Модель переменных сессии пользователья.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Backend\DebugToolbar\Model
 * @since 1.0
 */
class SessionNodes extends NodesModel
{
    /**
     * Собирает все переменные сессии.
     * 
     * @param string $var Название переменной.
     * 
     * @return array
     */
    protected function collectVar($var)
    {
        $var = ltrim($var, 'root::');
        $arr = explode('::', $var);
        $list = $_SESSION;
        for ($i = 0; $i < sizeof($arr); $i++) {
            $key = $arr[$i];
            if (array_key_exists($key, $list)) {
                $list = $list[$key];
            } else
                return array();
        }
        return $list;
    }

    /**
     * Возвращает все переменные сессии.
     * 
     * @return array
     */
    protected function getSessionVars()
    {
        $nodes = array();
        if ($this->nodeId == 'root') {
            $vars = $_SESSION;
            foreach ($vars as $key => $value) {
                $text = $key;
                if (is_array($value)) {
                    $leaf = false;
                } else {
                    $leaf = true;
                    if (mb_strlen($value) > 60)
                        $value = mb_substr($value, 0, 60);
                    $text .= ': <em>' . $value . '</em>';
                }
    
                $nodes[] = array('id' => 'root::' . $key, 'text' => $text, 'leaf' => $leaf);
            }
        } else {
            $vars = $this->collectVar($this->nodeId);
            foreach ($vars as $key => $value) {
                $text = $key;
                if (is_array($value)) {
                    $leaf = false;
                } else {
                    $leaf = true;
                    if (mb_strlen($value) > 60)
                        $value = mb_substr($value, 0, 60);
                    $text .= ': <em>' . $value . '</em>';
                }
                $nodes[] = array('id' => $this->nodeId . '::' . $key, 'text' => $text, 'leaf' => $leaf);
            }
        }
        return $nodes;
    }

    /**
     * Собирает все переменные сессии.
     * 
     * @param string $var Название переменной.
     * 
     * @return array
     */
    protected function collectSessionVars($vars)
    {
        $nodes = array();
        foreach ($vars as $key => $value) {
            $text = $key;
            if (is_array($value)) {
                $nodes[] = array('text' => '' . $key, 'leaf' => false, 'children' => $this->collectSessionVars($value));
            } else {
                if (mb_strlen($value) > 100)
                    $value = mb_substr($value, 0, 100) . '...';
                $text .= ': <em>' . $value . '</em>';
                $nodes[] = array('text' => $text, 'leaf' => true);
            }
        }
        return $nodes;
    }

    /**
     * Возвращет все узлы дерева.
     * 
     * @return array
     */
    public function getAllNodes()
    {
        return $this->collectSessionVars(Gm::$app->session->all());
    }
}
