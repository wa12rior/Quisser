<?php

namespace App\Controllers;
use App\Models\User;
use App\Models\UserDesc;
use Slim\Views\Twig as View;

class ProfileController extends Controller {
    private $user;
    private $userDesc;

    public function index($request, $response, $name) {
        $this->user = User::where( 'name', $request->getAttribute('route')->getArguments()['name'])->first();
        
        $this->userDesc = UserDesc::where( 'user_id', $this->user['id'])->first();
        
        if ($this->user['name'] === $name['name']) {
            return $this->view->render($response, 'profile/profile.twig', [
                'user' => $name['name'],
                'email' => $this->user['email'],
                'dateOfBirth' => $this->userDesc['date_of_birth'] ? $this->userDesc['date_of_birth'] : 'Not set',
                'description' => $this->userDesc['description'] ? $this->userDesc['description'] : 'Not set',
                'age' => $this->userDesc['age'] ? $this->userDesc['age'] : 'Not set',
                'location' => $this->userDesc['location'] ? $this->userDesc['location'] : 'Not set',
                'fullName' => $this->userDesc['full_name'] ? $this->userDesc['full_name'] : 'Not set',
                'created_at' => $this->user['created_at']

            ]);
        } else {
            return $response->withRedirect($this->container->router->pathFor('home'));
        }
    }
}