<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Controllers\Controller;

class QuizModifyController extends Controller {
    public function modifyData($request, $response, $args) {
        $user = User::where('id', $_SESSION['user'])->first()->getAttribute('name');
        
        $quiz = Quiz::where('user_id', '=', $_SESSION['user'])->where('URL', '=', $user . '/' . $args['url'])->first();

        if ($quiz == NULL) {
            return $response->withRedirect($this->router->pathFor('home'));
        }

        $title = $quiz->getAttribute('title');
        $body = $quiz->getAttribute('body');
        $randQ = $quiz->getAttribute('randomize_questions');
        $randA = $quiz->getAttribute('randomize_answers');
        $mtp = $quiz->getAttribute('multiple_choice');
        $slide = $quiz->getAttribute('slide_questions');

        return $this->view->render($response, 'templates/modify.twig', [
                'title' => $title,
                'body' => $body,
                'randQ' => $randQ,
                'randA' => $randA,
                'mtp' => $mtp,
                'slide' => $slide,
                'url' => substr($request->getUri()->getPath(), 1),
        ]);
    }

    public function postChangedData($request, $response) {
        $randomize = [
            'questions' => ($_POST['randomizeQuestions'] == 'true') ? 1 : 0,
            'answers' => ($_POST['randomizeAnswers'] == 'true') ? 1 : 0,
        ];

        $multipleChoice = ($_POST['multipleChoice'] == 'true') ? 1 : 0;
        $slideQuestions = ($_POST['slideQuestions'] == 'true') ? 1 : 0;

        $pattern = $_POST['pattern'];
        $quizTitle = $_POST['quizTitle'];

        $inp = explode('/', $_POST['path']);
        $parsedInp = explode('.', $inp[1]);

        $slug = explode(' ', strtolower($quizTitle));
        $slug = implode('-', $slug);

        $url = User::find($_SESSION['user'])->getAttribute('name') . '/' . $inp[1];

        $quiz = Quiz::where('URL', $url)->first();

        $newUrl = User::find($_SESSION['user'])->getAttribute('name') . '/' . $slug . '.' . $parsedInp[1];

        $quiz->multiple_choice = $multipleChoice;
        $quiz->slide_questions = $slideQuestions;
        $quiz->randomize_questions = $randomize['questions'];
        $quiz->randomize_answers = $randomize['answers'];
        $quiz->title = $quizTitle;
        $quiz->body = $pattern;
        $quiz->url = $newUrl;

        $quiz->save();

        $this->flash->addMessage('info', 'Quiz został zaktualizowany.');
    }
}