<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Models\UserDesc;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class AuthController extends Controller {
    public function getSignOut($request, $response) {
        $this->auth->logout();
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignIn($request, $response) {
        return $this->view->render($response, 'auth/signin.twig');
    }

    public function postSignIn($request, $response) {
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );

        if (!$auth) {
            $this->flash->addMessage('error', 'Złe dane użytkownika.');
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }

        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignUp($request, $response) {
        return $this->view->render($response, 'auth/signup.twig');
    }

    public function postSignUp($request, $response) {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'name' => v::noWhitespace()->notEmpty()->alpha(),
            'password' => v::noWhitespace()->notEmpty(),
        ]);

        // set up a middleware to dynamicly change form state in case of errors
        
        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $user = User::create([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
        ]);

        $desc = UserDesc::create([
            'user_id' => $user->id
        ]);

        $this->flash->addMessage('info', 'Twoje konto zostało pomyślnie utworzone!');
        // sign in immediately after the signup
        $this->auth->attempt($user->email, $request->getParam('password'));

        return $response->withRedirect($this->router->pathFor('home'));
    }
}