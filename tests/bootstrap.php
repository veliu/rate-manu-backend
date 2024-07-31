<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\ErrorHandler;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

passthru(sprintf(
    'APP_ENV=%s php "%s/../bin/console" cache:clear --no-warmup',
    $_ENV['APP_ENV'],
    __DIR__
));

passthru(sprintf(
    'APP_ENV=%s php "%s/../bin/console" doctrine:schema:drop -f -q',
    $_ENV['APP_ENV'],
    __DIR__
));

passthru(sprintf(
    'APP_ENV=%s php "%s/../bin/console" doctrine:schema:create -q',
    $_ENV['APP_ENV'],
    __DIR__
));

set_exception_handler([new ErrorHandler(), 'handleException']);
