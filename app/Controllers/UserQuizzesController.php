<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Answer;
use App\Controllers\Controller;

class UserQuizzesController extends Controller {
    public function index($request, $response) {
        $quizzes = Quiz::where('user_id', $_SESSION['user'])->orderBy('created_at', 'DESC')->get();

        $user = User::where('id', $_SESSION['user'])->first()->getAttribute('name');

        foreach ($quizzes as $quiz) {
            $slug = explode('/',$quiz->getAttribute('URL'));
            $titles[] = $quiz->getAttribute('title');
            $slugs[] = $slug[1];
            $created[] = $quiz->getAttribute('created_at');
            $updated[] = $quiz->getAttribute('updated_at');
        }

        $answers = Answer::where('user_id', $_SESSION['user'])->orderBy('created_at', 'DESC')->get();

        foreach ($answers as $index => $answer) {
            $ids[] = $answer->getAttribute('quiz_id');
            $authors[] = User::where('id', Quiz::where('id', $ids[$index])->first()->getAttribute('user_id'))->first()->getAttribute('name');
            $doneTitles[] = Quiz::where('id', $ids[$index])->first()->getAttribute('title');
            $paths[] = Quiz::where('id', $ids[$index])->first()->getAttribute('URL');
            $newCreated[] = $answer->getAttribute('created_at');
        }

        foreach($paths as $index => $path) {
            $partial = explode('/', $path);
            $newPaths[] = $authors[$index] . '/' . $partial[1];
        }

        return $this->view->render($response, 'templates/myQuizzes.twig', [
            'created' => [
                'titles' => $titles,
                'author' => $user,
                'slugs' => $slugs,
                'created' => $created,
                'updated' => $updated,
            ],
            'done' => [
                'titles' => $doneTitles,
                'author' => $authors,
                'slugs' => $newPaths,
                'created' => $newCreated,
            ]
        ]);
    }
}