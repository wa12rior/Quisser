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

    $this->get('/myQuizzes', 'UserQuizzesController:index')->setName('myQuizzes');
    
    $this->get('/auth/signout', 'AuthController:getSignOut')->setName('auth.signout');

    $this->get('/auth/password/change', 'PasswordController:getChangePassword')->setName('auth.password.change');
    $this->post('/auth/password/change', 'PasswordController:postChangePassword');

    $this->get('/modify/account', 'UserModifyController:index')->setName('modify.user');
    $this->post('/modify/account', 'UserModifyController:postChanges')->setName('modify.user');
    
    $this->get('/modify/{url}', 'QuizModifyController:modifyData')->setName('modify.quiz');
    $this->get('/delete/{url}', 'QuizDeleteController:deleteQuiz');

    $this->get('/stats/{url}', 'StatisticsController:index')->setName('stats');
    $this->get('/view/{creator}/{url}', 'QuizCorrectedController:index');

    $this->post('/modify/{url}', 'QuizModifyController:postChangedData');

    $this->get('/{name}/{url}', 'QuizController:index')->setName('quiz');
    
})->add(new AuthMiddleware($container));

$app->post("/test2", function($req, $res) {  
    
    $files = $req->getUploadedFiles();

    foreach($files as $fileName => $file) 
    {
        $this['UploadService']->uploadImage($file);
    }

    exit(var_dump($req->getUploadedFiles()));
});

$app->get('/{name}', 'ProfileController:index')->setName('profile');

