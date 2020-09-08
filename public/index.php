<?php

declare(strict_types=1);

include_once dirname(__DIR__).'/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

(new Dotenv(true))->loadEnv(dirname(__DIR__).'/.env');

$url = $_SERVER['REQUEST_URI'];
$urlPath = parse_url($url, PHP_URL_PATH);

// A very simple router
switch ($urlPath) {
    case '/':
    case '/step-1':
        (new \App\Controller\AuthenticationStep1())->run($url);
        break;
    case '/step-2':
        (new \App\Controller\AuthenticationStep2())->run($url);
        break;
    case '/refresh':
        (new \App\Controller\AuthenticationRefresh())->run($url);
        break;
    case '/dashboard':
        (new \App\Controller\Dashboard())->run($url);
        break;
    case '/candidate-return':
        (new \App\Controller\CandidateReturn())->run($url);
        break;
    case '/create-role':
        (new \App\Controller\CreateRole())->run($url);
        break;
    case '/download-role-category':
        (new \App\Controller\DownloadRoleCategory())->run($url);
        break;
    case '/create-test':
        (new \App\Controller\CreateTest())->run($url);
        break;
    case '/get-match':
        (new \App\Controller\CreateMatch())->run($url);
        break;
    case '/find':
        (new \App\Controller\CreateFind())->run($url);
        break;
    default:
        echo 'Page not found';
}
