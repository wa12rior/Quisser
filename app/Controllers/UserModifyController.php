<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\UserDesc;
use App\Controllers\Controller;

class UserModifyController extends Controller {
    public function index($request, $response) {
        $user = UserDesc::where('user_id', $_SESSION['user'])->first();

        return $this->view->render($response, 'templates/userModify.twig', [
            'full_name' => $user->getAttribute('full_name'),
            'description' => $user->getAttribute('description'),
            'age' => $user->getAttribute('age'),
            'location' => $user->getAttribute('location'),
            'date_of_birth' => $user->getAttribute('date_of_birth'),
        ]);
    }

    public function postChanges($request, $response) {
        $user = UserDesc::where('user_id', $_SESSION['user'])->first();
        
        $user->date_of_birth = $request->getParam('birthDate');
        $user->description = $request->getParam('desc');
        $user->age = $request->getParam('age');
        $user->location = $request->getParam('location');
        $user->full_name = $request->getParam('surname');
        $user->save();

        $this->flash->addMessage('info', 'Pomyślnie zmieniono informacje o użytkowniku');

        return $response->withRedirect($this->router->pathFor('home'));
    } 
}