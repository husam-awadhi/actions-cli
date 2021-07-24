<?php


namespace App;

/**
 * helper class to write messages to output 
 * with style and color. 
 * 
 * configuration:
 *  add cli in config with 2 elements, 
 *  color and style.
 *
 * @since 1.0.0
 * @author Husam A <husam.awadhi@gmail.com>
 */
class Write
{

    public const RED = 'red';
    public const BLUE = 'blue';
    public const GREEN = 'green';
    public const WHITE = 'white';
    public const BLACK = 'black';
    public const BOLD = 'bold';
    public const UNDERLINED = 'underlined';

    /** @var array list of supported colors */
    static private $colors = [
        self::RED => "31",
        self::BLUE => "34",
        self::GREEN => "32",
        self::WHITE => "97",
        self::BLACK => "90",
    ];

    /** @var array list of supported styles */
    static private $styles = [
        self::BOLD => "1",
        self::UNDERLINED => "4",
    ];

    /** @var string end sequence */
    const END = "\e[0m";

    /** @var string start sequence */
    const START = "\e[";

    /**
     * parse style and color then write to output
     *
     * @param string $message
     * @param string $style
     * @param string $color
     * @return void
     * @access public
     */
    static public function echo(string $message, string $style = '', string $color = 'black'): void
    {
        $fullMessage = self::prepare($message, $style, $color);

        fwrite(STDOUT, "{$fullMessage}\n");
        try{
            if (Config::getValue('log')) Helpers::log("[" . self::class . "] {$message}");
        }catch (\Exception $e){
            //do something
        }
    }

    /**
     * parse style and color and return final message
     *
     * @param string $message
     * @param string $style
     * @param string $color
     * @return string
     * @access public
     */
    static public function prepare(string $message, string $style = '', string $color = 'black'): string
    {
        $prefix = self::getPrefix($style, $color);
        $suffix = self::getSuffix((strlen($prefix) > 0));

        return "{$prefix}{$message}{$suffix}";
    }

    /**
     * prepare ANSI/VT100 Control sequences
     *
     * @param string $style
     * @param string $color
     * @return string
     * @access private
     */
    static private function getPrefix(string $style, string $color): string
    {
        $ret = '';
        $matches = [];

        try {
            if (Config::getValue('cli.color') == true && isset(self::$colors[$color])) $matches[] = self::$colors[$color];
            if (Config::getValue('cli.style') == true && isset(self::$styles[$style])) $matches[] = self::$styles[$style];
        } catch (\Exception $e) {
            $matches = [];
        }

        if ($matches) $ret = implode(';', $matches);
        if ($ret) $ret = self::START . "{$ret}m";

        return $ret;
    }

    /**
     * add end sequence if there is any styling or color
     *
     * @param boolean $endStyle
     * @return string
     * @access private
     */
    static private function getSuffix(bool $endStyle = false): string
    {
        return ($endStyle ? self::END : '');
    }
}
