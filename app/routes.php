<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', 'HomeController:index')->setName('home');

$app->group('', function () {
    $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $this->post('/auth/signup', 'AuthController:postSignUp');

    $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $this->post('/auth/signin', 'AuthController:postSignIn');
})->add(new GuestMiddleware($container));


$app->group('', function () {
    $this->post('/check', 'QuizHandlerController:index')->setName('check');

    $this->get('/create', 'QuizCreationController:index')->setName('create');
    $this->post('/create', 'QuizCreationController:postQuiz');
    
    $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

    $this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
    $this->post('/auth/password/change', 'PasswordController:postChangePassword');

    $this->get('/modify/{url}', 'QuizModifyController:index')->setName('modify');
    $this->get('/{name}/{url}', 'QuizController:index')->setName('quiz');
    
})->add(new AuthMiddleware($container));

$app->get('/{name}', 'ProfileController:index')->setName('profile');