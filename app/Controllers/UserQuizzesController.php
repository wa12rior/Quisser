<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Controllers\Controller;

class UserQuizzesController extends Controller {
    public function index($request, $response) {
        $quizzes = Quiz::where('user_id', $_SESSION['user'])->get();

        foreach ($quizzes as $quiz) {
            $titles[] = $quiz->getAttribute('title');
            $slugs[] = $quiz->getAttribute('URL');
            $created[] = $quiz->getAttribute('created_at');
        }

        return $this->view->render($response, 'templates/myQuizzes.twig', [
            'created' => [
                'titles' => $titles,
                'slugs' => $slugs,
                'created' => $created,
            ],
            'done' => [

            ]
        ]);
    }
}