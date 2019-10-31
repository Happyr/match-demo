<?php

declare(strict_types=1);

include_once dirname(__DIR__).'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

(new Dotenv(true))->loadEnv(dirname(__DIR__).'/.env');

$url = $_SERVER['REQUEST_URI'];
$urlPath = parse_url($url, PHP_URL_PATH);
switch ($urlPath) {
    case '/':
    case '/step-1':
        (new \App\Controller\AuthenticationStep1())->run($url);
        break;
    case '/step-2':
        (new \App\Controller\AuthenticationStep2())->run($url);
        break;
    case '/dashboard':
        (new \App\Controller\Dashboard())->run($url);
        break;
    case '/candidate-return':
        (new \App\Controller\CandidateReturn())->run($url);
        break;
    default:
        echo 'Page not found';
}
