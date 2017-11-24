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
            $url = $quiz->getAttribute('URL');
            $questions = json_decode($quiz->getAttribute('body'));
            $randomizeQuestions = $quiz->getAttribute('randomizeQuestions');
            $randomizeAnswers = $quiz->getAttribute('randomizeAnswers');
            
            // echo '<pre>';
            // var_dump($questions);
            // echo '</pre>';
            // return;
            return $this->view->render($response, 'templates/quiz.twig', [
                'author' => $args['name'],
                'title' => $title,
                'questions' => $questions,
                'randomizeQuestions' => $randomizeQuestions,
                'randomizeAnswers' => $randomizeAnswers,
                'url' => $url,
            ]);
        }

        $this->flash->addMessage('error', 'Wrong quiz path.');

        return $response->withRedirect($this->router->pathFor('home'));
    }
}