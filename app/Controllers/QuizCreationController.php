<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Controllers\Controller;

class QuizCreationController extends Controller {
    public function index($request, $response) {
        return $this->view->render($response, 'templates/create.twig');
    }

    public function postQuiz($request, $response) {
        $randomize = [
            'questions' => $_POST['randomizeQuestions'] ? 1 : 0,
            'answers' => $_POST['randomizeAnswers'] ? 1 : 0,
        ];

        $pattern = $_POST['pattern'];
        $quizTitle = $_POST['quizTitle'];

        $slug = explode(' ', strtolower($quizTitle));
        $slug = implode('-', $slug);

        $url = User::find($_SESSION['user'])->getAttribute('name') . '/' . $slug . '.';

        $quiz = Quiz::create([
            'title' => $quizTitle,
            'body' => $pattern,
            'randomize_questions' => $randomize['questions'],
            'randomize_answers' => $randomize['answers'],
            'user_id' => $_SESSION['user']
        ]);

        $quiz->url = $url . $quiz->id;
        $quiz->save();
        $this->flash->addMessage('info', 'You have created quiz with url https://www.frizcode.me/' . $quiz->url);
    }
}