<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Quiz;
use Slim\Views\Twig as View;

class QuizController extends Controller {

    public function index($request, $response, $args) {
        $query = $args['name'] . '/' . $args['url'];
        
        $user = User::where('name', $args['name'])->first();
        $quiz = Quiz::where('URL', $query)->first();
        
        if ($quiz !== NULL) {

            $title = $quiz->getAttribute('title');
            $question = $quiz->getAttribute('body');
            $randomizeQuestions = $quiz->getAttribute('randomizeQuestions');
            $randomizeAnswers = $quiz->getAttribute('randomizeAnswers');
            var_dump($question);
            return;
            // return $this->view->render($response, 'templates/quiz.twig', [
            //     'name' => $quiz,
            // ]);
        }

        $this->flash->addMessage('error', 'Wrong quiz path.');

        return $response->withRedirect($this->router->pathFor('home'));
    }
}