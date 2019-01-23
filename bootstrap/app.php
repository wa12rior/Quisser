<?php

use Respect\Validation\Validator as v;

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Europe/Warsaw');

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'rootPath' => "../",
        'db' => [
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'database'  => 'Quisser',
            'username'  => 'root',
            'password'  => 'root',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ],
        'images' => [
            'uploadPath' => "/public/images/upload/",
            'manager' => [
                'driver' => 'imagick'
            ]
        ]
    ],
]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
    
$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};


$container['filesystem'] = function($container) {
    return \App\Services\FilesystemService::fromRelativePath(dirname(__FILE__) . "/" . $container['settings']['rootPath']);
};

$container['auth'] = function ($container) {
    return new \App\Auth\Auth;
};

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages;
};

$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false,
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
};


$container['validator'] = function($container) {
    return new App\Validation\Validator;
};

$container['HomeController'] = function($container) {
    return new \App\Controllers\HomeController($container);
};

$container['ProfileController'] = function($container) {
    return new \App\Controllers\ProfileController($container);
};

$container['QuizCreationController'] = function($container) {
    return new \App\Controllers\QuizCreationController($container);
};

$container['QuizModifyController'] = function($container) {
    return new \App\Controllers\QuizModifyController($container);
};

$container['QuizCorrectedController'] = function($container) {
    return new \App\Controllers\QuizCorrectedController($container);
};

$container['StatisticsController'] = function($container) {
    return new \App\Controllers\StatisticsController($container);
};

$container['QuizHandlerController'] = function($container) {
    return new \App\Controllers\QuizHandlerController($container);
};

$container['UserModifyController'] = function($container) {
    return new \App\Controllers\UserModifyController($container);
};

$container['QuizDeleteController'] = function($container) {
    return new \App\Controllers\QuizDeleteController($container);
};

$container['UserQuizzesController'] = function($container) {
    return new \App\Controllers\UserQuizzesController($container);
};

$container['QuizController'] = function($container) {
    return new \App\Controllers\QuizController($container);
};

$container['AuthController'] = function($container) {
    return new \App\Controllers\Auth\AuthController($container);
};

$container['PasswordController'] = function($container) {
    return new \App\Controllers\Auth\PasswordController($container);
};

$container['csrf'] = function ($container) {
    return new \Slim\Csrf\Guard;
};


$container['UploadService'] = function($container) {
    $manager = new \Intervention\Image\ImageManager($container['settings']['images']['manager']);

    return new \App\Services\UploadService($manager, $container);
};

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\CredentialsMiddleware($container));

$app->add(new \App\Middleware\CsrfViewMiddleware($container));


$app->add($container->csrf);

v::with('App\\Validation\\Rules\\');

require __DIR__ . '/../app/routes.php';
