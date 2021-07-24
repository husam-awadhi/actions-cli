<?php

namespace App\Actions\Plugins;

use App\Actions\ActionPluginInterface;
use App\Actions\Actions;
use App\Config;
use App\Write;

/**
 * help action plugin class
 * display help message using -h
 *
 * @since 1.0.0
 * @author Husam A <husam.awadhi@gmail.com>
 */
class Help implements ActionPluginInterface
{
    /** @var string plugin code */
    private $code = 'h';

    /** @var string plugin name */
    private $name = 'help';

    /** @var int plugin weight */
    private $weight = 0;

    /** @var bool plugin require arguments */
    private $arguments = false;

    /** @var bool continue processing other options after this */
    private $continue = false;

    /** @var string last error message*/
    private $errorMessage = '';

    /**
     * @inheritDoc
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function requireArguments(): bool
    {
        return $this->arguments;
    }

    /**
     * @inheritDoc
     */
    public function getContinue(): bool
    {
        return $this->continue;
    }

    /**
     * @inheritDoc
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * @inheritDoc
     */
    public function setArguments(string $argument): void
    {
        //no args
    }

    /**
     * @inheritDoc
     */
    public function getArguments(): string
    {
        return ''; //no args
    }

    /**
     * @inheritDoc
     */
    public function invokeAction(Actions $actions): bool
    {
        $message = '';
        foreach ($actions->getRegisteredPlugins() as $actionCode => $actionName) {
            $message .= $actions->invokePlugin($actionCode, 'getHelp');
        }
        Write::echo($message, '', '');
        $actions->setContinue(false);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getHelp(): string
    {
        return ""
            . Write::prepare(Config::getValue('env.app') . " "
                . Config::getValue('env.version') . " of " . Config::getValue('env.date')
                . ", by Husam Awadhi <husam.awadhi@gmail.com>.", 'BOLD')
            . "\n"
            . Write::prepare("Usage:") . "\n"
            . Write::prepare("-h\t\t\t display usage and about information.") . "\n";
    }

    /**
     * @inheritDoc
     */
    public function getError(): string
    {
        return $this->errorMessage;
    }
}
