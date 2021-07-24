<?php

/**
 * Application constants.
 *
 * @package  actions-cli
 * @author   Husam A.
 */

/*
|--------------------------------------------------------------------------
| Define Root directory
|--------------------------------------------------------------------------
|
| application base directory
|
*/
if (!defined('ROOT')) define('ROOT', __DIR__);


/*
|--------------------------------------------------------------------------
| Define Log directory
|--------------------------------------------------------------------------
|
| debug and error logs directory 
|
*/
if (!defined('LOG')) define('LOG', ROOT . "/var/logs");

/*
|--------------------------------------------------------------------------
| Define cache directory
|--------------------------------------------------------------------------
|
| twig templates cache directory 
|
*/
if (!defined('CACHE')) define('CACHE', ROOT . "/var/cache");

/*
|--------------------------------------------------------------------------
| Define Log directory
|--------------------------------------------------------------------------
|
| debug and error logs directory 
|
*/
if (!defined('CONFIG')) define('CONFIG', ROOT . "/config");

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/
require ROOT . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| define global functions 
|--------------------------------------------------------------------------
|
| to be called anywhere in the library 
|
*/

if(!function_exists('d')) {
    /**
     * called to echo trace and dump $args
     *
     * @param mix $args
     * @return void
     */
    function d($args)
    {
        $e = new Exception();
        $dump['Trace'] = $e->getTraceAsString();
        $dump['Message'] = $args; 
        var_dump($dump);
    }
}

if(!function_exists('dd')) {
    /**
     * called to dump $args and die
     *
     * @param mix $args
     * @return void
     */
    function dd($args)
    {
        d($args);
        die;
    }
}
