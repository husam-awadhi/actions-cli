# actions-cli

simple cli tool with a plugin design

## running the action tool

using php reserved variable [$argv](https://www.php.net/manual/en/reserved.variables.argv.php)
```php
 $command = array_shift($argv);
 // run
 $actions = new App\Actions\Actions($argv);
 $actions->processCommand();
```
the tool require at least help plugin to be registered to work. 
```php
//actions is initiated.
$actions->addPlugin(new App\Actions\Plugins\Help()); // -h display help message
```

## plugins

when creating a plugin make sure to configure the weight correctly as the sequence of process is depending on that in ascending order.

## Write class

the tool uses `App\Write` to write output.


you can read more by navigating to `project-dir/docs/index.html`
