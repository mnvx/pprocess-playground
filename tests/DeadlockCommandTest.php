<?php

use Mnvx\PProcess\AsyncTrait;
use Mnvx\PProcess\Command\CommandSet;
use PHPUnit\Framework\TestCase;

class DeadlockCommandTest extends TestCase
{
    use AsyncTrait;

    public function testImport()
    {
        $asnycCommand = new CommandSet(
            [   // Тестируемые команды
                './cli app:deadlock-one',
                './cli app:deadlock-two'
            ],
            dirname(__DIR__), // Каталог, из которого будет запускаться команда
            1 // Количество запускаемых экземпляров команд
        );
        // Запуск проверки
        $this->assertAsyncCommands($asnycCommand);
    }
}