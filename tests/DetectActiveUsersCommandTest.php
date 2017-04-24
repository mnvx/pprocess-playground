<?php

use Mnvx\PProcess\AsyncTrait;
use Mnvx\PProcess\Command\Command;
use PHPUnit\Framework\TestCase;

class DetectActiveUsersCommandTest extends TestCase
{
    use AsyncTrait;

    public function testImport()
    {
        $asnycCommand = new Command(
            './cli app:detect-active-users', // Тестируемая команда
            dirname(__DIR__), // Каталог, из которого будет запускаться команда
            3 // Количество запускаемых экземпляров команд
        );
        // Запуск проверки
        $this->assertAsyncCommand($asnycCommand);
    }
}