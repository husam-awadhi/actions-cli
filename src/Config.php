<?php

/**
 * Config class file
 */

namespace App;

use App\Helpers;

/**
 * load configs from file
 * can load it into a one or multi dimension array.
 * 
 * @since 1.0.0
 * @author Husam A <husam.awadhi@gmail.com>
 */
class Config
{

    /** @var string default message if no configuration found */
    private const DEFAULT = "this ain't it chief";

    /** @var string file directory*/
    private const CONFIG_FILE = CONFIG . "/app.json";

    /** @var string error message */
    private const ERROR = "Error loading config file. please check file exist and it's in valid json format";

    /**
     * load config file from directory and return it as a 
     * one dimension array if format is true
     *
     * @param boolean $format
     * @return array
     * @access protected
     */
    static protected function loadConfig($format = true): array
    {
        static $config;

        if (!$config && is_file(self::CONFIG_FILE)) {
            $config = json_decode(file_get_contents(self::CONFIG_FILE), true);
            if ($format && isset($config['config'])) $config['config'] = Helpers::toOneDimension($config['config']);
        }

        if (!$config) throw new \Exception(self::ERROR);

        return $config;
    }

    /**
     * return value from config file or whole configurations if key is null
     *
     * @param string $key
     * @return string|array
     * @access public
     */
    static public function getValue($key = null): ?string
    {

        $config = self::loadConfig();

        $value = ($key !== null) ? (isset($config['config'][$key]) ? $config['config'][$key] : Config::DEFAULT) : $config['config'];

        return $value;
    }
}
