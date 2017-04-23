<?php

namespace App\Command;

use Doctrine\DBAL\Migrations\Tools\Console\Command;
use Symfony\Component\Console\Application;

class Initializer
{
    public static function create()
    {
        $cli = new Application();

        $rootDir = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR;

        // Register commands for Doctrine Migrations
        $config = new \Doctrine\DBAL\Configuration();
        $connectionParams = require $rootDir . 'config/database.php';
        $db = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        $helperSet = $cli->getHelperSet();
        $helperSet->set(new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($db), 'db');
        $helperSet->set(new \Symfony\Component\Console\Helper\QuestionHelper(), 'dialog');

        $cli->addCommands([
            new Command\ExecuteCommand(),
            new Command\GenerateCommand(),
            new Command\MigrateCommand(),
            new Command\StatusCommand(),
            new Command\VersionCommand(),
        ]);

        // Register commands from src/Command
        $commandPath = $rootDir . 'src/Command/';
        foreach (new \DirectoryIterator($commandPath) as $fileInfo) {
            if (is_file($commandPath . $fileInfo->getFilename()) && substr($fileInfo->getFilename(), -11) === 'Command.php') {
                $command = '\\App\\Command\\' . substr($fileInfo->getFilename(), 0, -4);
                if (class_exists($command)) {
                    $cli->add(new $command);
                }
            }
        }

        return $cli;
    }
}