<?php

/**
 * Helpers class file
 */

namespace App;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * helpers for application to function
 * and basic logging 
 * 
 * @since 1.0.0
 * @author Husam A <husam.awadhi@gmail.com>
 */
class Helpers
{

    /**
     * write log as info with [APP] tag and 
     * levels
     *
     * @param string $msg
     * @param string $level
     * @return void
     * @access public
     */
    public function write($msg, $level): void
    {
        self::log("[APP] [$level] $msg", 'info');
    }

    /**
     * write message into log file
     *
     * @param string $msg
     * @param string $type
     * @return void
     * @access public
     */
    static public function log($msg, $type = ''): void
    {
        $logger = self::getLogger();

        switch (strtoupper($type)) {
            case "WARNING":
                $func = 'warning';
                break;
            case "ERROR":
                $func = 'error';
                break;
            default:
                $func = 'info';
        }

        if ($logger instanceof Logger) $logger->$func($msg);
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return Logger
     * @access public
     */
    static public function getLogger($name = 'app'): Logger
    {
        static $logger;

        if (!$logger && Config::getValue("log")) {
            $logger = new Logger($name);
            $logger->pushHandler(new StreamHandler(LOG . '/' . $name . '-logs-' . date('Ymd') . '.log', Logger::DEBUG));
        }

        return $logger;
    }

    /**
     * convert multi dimensions array to one dimension
     * example: 
     * ? before 
     * [ 'key1' => [ 'key2' => 'value' ] ] 
     * ? after
     * [ 'key1.key2' => 'value' ]
     * 
     * ! recursive function !
     * @param array $array
     * @param boolean $nested_key
     * @param array $final
     * @return array
     * @access public
     */
    static public function toOneDimension($array = array(), $nested_key = false, array $final = array()): array
    {

        foreach ($array as $key => $element) {

            $full_key = ($nested_key === false ? '' : $nested_key . '.') . $key;
            if (!is_array($array[$key])) $final[$full_key] = $element;
            else $final = array_merge($final, self::toOneDimension($array[$key], $full_key, $final));
        }

        return $final;
    }
}
