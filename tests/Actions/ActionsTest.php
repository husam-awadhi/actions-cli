<?php

declare(strict_types=1);

use App\Actions\Actions;
use App\Actions\Plugins\Help;
use PHPUnit\Framework\TestCase;

final class ActionsTest extends TestCase
{
    /** @var Actions action object */
    private $action;

    protected function setUp(): void
    {
        $this->action = new Actions([]);
    }

    public function testRequireHelpPluginToFunction(): void
    {
        $this->expectException(\Exception::class);
        $this->action->processCommand();
    }

    public function testCanNotRegisterDuplicatePlugins(): void
    {
        $this->expectException(\Exception::class);
        $this->action->addPlugin(new Help());
        $this->action->addPlugin(new Help());

        $this->action->processCommand();
    }

    public function testCanRegisterPlugins(): void
    {
        $help = new Help();
        $this->action->addPlugin($help);

        $this->assertContainsEquals($help->getName(), $this->action->getRegisteredPlugins());
    }

    public function testRejectUnknownOptions(): void
    {
        $this->expectException(\Exception::class);
        $this->action = new Actions(['-x']);
        $this->action->addPlugin(new Help());

        $this->action->processCommand();
    }

    protected function tearDown(): void
    {
        $this->action = null;
    }
}
