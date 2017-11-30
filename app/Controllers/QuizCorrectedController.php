<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Answer;
use App\Controllers\Controller;

class QuizCorrectedController extends Controller {
    public function index($request, $response, $args) {

        $user = User::where('id', $_SESSION['user'])->first();
        $creator = $args['creator'];
        $query = $creator . '/' . $args['url'];
        
        $mainquiz = Quiz::where('URL', $query)->first();
        $quiz = Answer::where('user_id', $_SESSION['user'])->where('quiz_id', $mainquiz->getAttribute('id'))->latest()->first();
        $body = $quiz->getAttribute('body');
        $body_bad = $quiz->getAttribute('body_bad');

        return $this->view->render($response, 'templates/view.twig', [
            'author' => $creator,
            'title' => $mainquiz->getAttribute('title'),
            'questions' => json_decode($mainquiz->getAttribute('body')),
            'good' => json_decode($body, true),
            'bad' => json_decode($body_bad, true),
        ]);
    }

}