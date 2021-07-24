<?php


namespace App\Actions;

/**
 * Main class to process command. 
 *
 * @since 1.0.0
 * @author Husam A <husam.awadhi@gmail.com>
 */
class Actions
{
    /** @var string action parse error */
    const INVALID_OPTION = "%s is an invalid option. Use -h for more info";

    /** @var string argument parse error */
    const INVALID_ARGUMENT = "Improper use of option %s, expecting argument. Use -h for more info";

    /** @var string argument performing error */
    const ERROR_ARGUMENT = "Error performing %s action. %s is not a valid %s.";

    /** @var string argument performing error */
    const ERROR_DUP_PLUGIN = "%s option is mentioned more than once. Use -h for more info";

    /** @var string argument performing error */
    const METHOD_NOT_FOUND = "Unable to call method %s in %s. Use -h for more info";

    /** @var array list of options to run*/
    protected $options = [];

    /** @var array ActionPluginInterface objects */
    protected $registeredPlugins = [];

    /** @var bool continue process command */
    protected $continue = true;



    public function __construct(array $actions)
    {
        $this->rawActions = $actions;
    }

    /**
     * returns continue value
     *
     * @return boolean
     * @access public
     */
    public function getContinue(): bool
    {
        return $this->continue;
    }

    /**
     * sets continue value
     *
     * @return boolean
     * @access public
     */
    public function setContinue(bool $continue) : void
    {
        $this->continue = $continue;
    }

    /**
     * register plugin after validation
     *
     * @param ActionPluginInterface $plugin
     * @return void
     * @access public
     */
    public function addPlugin(ActionPluginInterface $plugin): void
    {
        if ($this->isRegistered($plugin->getCode()))
            throw new \Exception(sprintf(self::ERROR_DUP_PLUGIN, $plugin->getName()));

        $this->register($plugin);
    }

    /**
     * validate and checks if plugin is registered
     *
     * @param String $code
     * @return boolean
     * @access public
     */
    public function isRegistered(String $code): bool
    {
        return (isset($this->registeredPlugins[$code])
            && $this->registeredPlugins[$code] instanceof ActionPluginInterface
            ? true
            : false);
    }

    /**
     * register plugin
     *
     * @param ActionPluginInterface $plugin
     * @return void
     * @access protected
     */
    protected function register(ActionPluginInterface $plugin): void
    {
        $this->registeredPlugins[$plugin->getCode()] = $plugin;
    }

    /**
     * returns array of registered plugins as ['code' => 'name', ...]
     *
     * @return array
     * @access public
     */
    public function getRegisteredPlugins() : array
    {
        $registered = [];
        foreach ($this->registeredPlugins as $plugin) {
            $registered[$plugin->getCode()] = $plugin->getName();
        }
        return $registered;
    }

    /**
     * start processing command with arguments from user input
     *
     * @return void
     * @access public
     */
    public function processCommand() : void
    {
        if (empty($this->options)) $this->parseActions($this->rawActions);
        if (empty($this->options)) $this->invokePlugin('h');

        foreach ($this->options as $weight => $actionCode) {
            $ok = $this->invokePlugin($actionCode);
            if (!$ok || !$this->continue) break;
        }
    }

    /**
     * call methods in plugins object.
     * ! only supports functions that accepts Action::class as first argument or no argument
     *
     * @param string $code
     * @param string $method
     * @return string|null
     * @access public
     */
    public function invokePlugin(string $code, $method = 'invokeAction'): ?string
    {
        if (!$this->isRegistered($code)) throw new \Exception(sprintf(self::INVALID_OPTION, $code));
        if (!method_exists($this->registeredPlugins[$code], $method)) throw new \Exception(sprintf(self::METHOD_NOT_FOUND, $method, $code));
        //TODO: better handling for methods with no / different arguments than invokeAction 
        return $this->registeredPlugins[$code]->$method($this);
    }

    /**
     * validate user input and remove and throws exception when not supported.
     * need to add plugin before calling this function else it will be considered as invalid
     *
     * @param array $options
     * @return void
     * @access public
     */
    public function parseActions(array $options): void
    {
        $validActions = [];
        for ($i = 0; $i < sizeof($options); $i++) {
            $actionCode = false;
            if (strpos($options[$i], '--') === 0) {
                $actionCode = substr($options[$i], 2);
            } else if (strpos($options[$i], '-') === 0) {
                $actionCode = substr($options[$i], 1);
            }

            if (!$this->isRegistered($actionCode))
                throw new \Exception(sprintf(self::INVALID_OPTION, $actionCode));

            if ($this->registeredPlugins[$actionCode]->requireArguments() == true) {
                if (!isset($options[$i + 1])) throw new \Exception(sprintf(self::INVALID_ARGUMENT, $options[$i]));

                $this->registeredPlugins[$actionCode]->setArguments($options[$i + 1]);
                $i++;
            }

            if (isset($validActions[$actionCode]))
                throw new \Exception(sprintf(self::ERROR_DUP_PLUGIN, $this->registeredPlugins[$actionCode]->getName()));

            $validActions[$this->registeredPlugins[$actionCode]->getWeight()] = $actionCode;
        }

        arsort($validActions, SORT_NUMERIC);
        $this->options =  $validActions;
    }
}
