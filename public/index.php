<?php

declare(strict_types=1);

include_once dirname(__DIR__).'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

(new Dotenv(true))->loadEnv(dirname(__DIR__).'/.env');

$url = $_SERVER['REQUEST_URI'];
switch ($url) {
    case '/':
    case '/step-1':
    (new \App\Controller\Step1())->run($url);
        break;
    case '/step-2':
        (new \App\Controller\Step1())->run($url);
        break;
    case '/step-3':
        (new \App\Controller\Step1())->run($url);
        break;
    default:
        echo 'Page not found';
}
