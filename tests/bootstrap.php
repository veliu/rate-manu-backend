<?php

use Doctrine\Deprecations\Deprecation;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Dotenv\Dotenv;
use Veliu\RateManu\Kernel;

require_once __DIR__.'/../vendor/autoload.php';

$dotenv = new Dotenv();
(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

if (class_exists(Deprecation::class)) {
    Deprecation::enableWithTriggerError();
}

function bootstrap(): void
{
    $kernel = new Kernel('test', true);
    $kernel->boot();

    $application = new Application($kernel);
    $application->setCatchExceptions(false);
    $application->setAutoExit(false);

    $application->run(new ArrayInput([
        'command' => 'doctrine:database:drop',
        '--if-exists' => '1',
        '--force' => '1',
    ]));

    $application->run(new ArrayInput([
        'command' => 'doctrine:database:create',
    ]));

    $application->run(new ArrayInput([
        'command' => 'doctrine:migration:migrate',
        '--no-interaction' => '1',
        '--allow-no-migration' => '1',
        '--env' => 'test',
    ]));

    $kernel->getContainer()->get('doctrine')->getConnection()->executeQuery('CREATE TABLE test (test VARCHAR(10))');
    $kernel->shutdown();
}

bootstrap();
