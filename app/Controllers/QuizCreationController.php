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
        var_dump($_POST);
        
        $randomize = [
            'questions' => ($_POST['randomizeQuestions'] == 'true') ? 1 : 0,
            'answers' => ($_POST['randomizeAnswers'] == 'true') ? 1 : 0,
        ];

        $multipleChoice = ($_POST['multipleChoice'] == 'true') ? 1 : 0;
        $slideQuestions = ($_POST['slideQuestions'] == 'true') ? 1 : 0;

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
            'multiple_choice' => $multipleChoice,
            'slide_questions' => $slideQuestions,
            'user_id' => $_SESSION['user']
        ]);

        $quiz->multiple_choice = $multipleChoice;
        $quiz->slide_questions = $slideQuestions;
        $quiz->url = $url . $quiz->id;
        $quiz->save();
        $this->flash->addMessage('info', 'Stworzyłeś quiz z URL: https://www.frizcode.me/' . $quiz->url);
    }
}