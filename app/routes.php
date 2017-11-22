<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', 'HomeController:index')->setName('home');
$app->get('/{name}', 'ProfileController:index')->setName('profile');

$app->group('', function () {
    $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');
    $this->post('/auth/signup', 'AuthController:postSignUp');

    $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
    $this->post('/auth/signin', 'AuthController:postSignIn');
})->add(new GuestMiddleware($container));


$app->group('', function () {
    $this->get('/auth/create', 'QuizCreationController:index')->setName('create');
    $this->post('/auth/create', 'QuizCreationController:postQuiz');
    
    $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

    $this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
    $this->post('/auth/password/change', 'PasswordController:postChangePassword');
    
    $this->get('/{name}/{url}', 'QuizController:index')->setName('quiz');
})->add(new AuthMiddleware($container));