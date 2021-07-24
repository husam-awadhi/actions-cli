<?php

namespace App\Actions;

/**
 * plugins interface. every plugin must implements this
 *
 * @since 1.0.0
 * @author Husam A <husam.awadhi@gmail.com>
 */
interface ActionPluginInterface
{

    /** @var string plugin code */
    public const INVALID_DIR = '%s argument is not an absolute path';

    /** @var string plugin code */
    public const MISSING_DEPENDENCY = 'missing %s plugin(s). unable to process option %s';

    /**
     * get plugin code. 
     * used for options identifier 
     *
     * @return string
     * @access public
     */
    public function getCode(): string;

    /**
     * get plugin name
     *
     * @return string
     * @access public
     */
    public function getName(): string;

    /**
     * indicates if plugin expects arguments after option
     *
     * @return boolean
     * @access public
     */
    public function requireArguments(): bool;

    /**
     * set user defined argument
     *
     * @return boolean
     * @access public
     */
    public function setArguments(string $argument): void;

    /**
     * get user defined argument
     *
     * @return string
     * @access public
     */
    public function getArguments(): string;

    /**
     * flag process to stop after invoking action
     *
     * @return boolean
     * @access public
     */
    public function getContinue(): bool;

    /**
     * get weight of option.
     * used in ordering plugins to invoke in ascending order
     *
     * @return integer
     * @access public
     */
    public function getWeight(): int;

    /**
     * main function to perform action
     *
     * @return boolean
     * @access public
     */
    public function invokeAction(Actions $actions): bool;

    /**
     * get last error encountered
     *
     * @return string
     * @access public
     */
    public function getError(): string;

    /**
     * get help information for plugin
     *
     * @return string
     * @access public
     */
    public function getHelp(): string;
}
