<?php

use App\Command\Initializer;
use Mnvx\PProcess\AsyncTrait;
use Mnvx\PProcess\Command\Command;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCommandTest extends TestCase
{
    use AsyncTrait;

    public function testImport()
    {
        $cli = Initializer::create();
        $command = $cli->find('app:delete');

        // Удаляем запись c external_id = 1,
        // чтобы проверить случай параллельного добавления одной и той же записи
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'externalId' => 1,
        ]);

        $asnycCommand = new Command(
            'echo -e \'{"id":1,"name":"account1"}\' | ./cli app:import', // Тестируемая команда
            dirname(__DIR__), // Каталог, из которого будет запускаться команда
            2 // Количество запускаемых экземпляров команд
        );
        // Запуск проверки
        $this->assertAsyncCommand($asnycCommand);
    }
}