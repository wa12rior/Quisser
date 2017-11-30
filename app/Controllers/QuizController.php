<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\History;
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
            $randomizeQuestions = $quiz->getAttribute('randomize_questions');
            $randomizeAnswers = $quiz->getAttribute('randomize_answers');
            $multipleChoice = $quiz->getAttribute('multiple_choice');
            $slideQuestions = $quiz->getAttribute('slide_questions');
             
            $history = History::create([
                'user_id' => $_SESSION['user'],
                'quiz_id' => $quiz->getAttribute('id')
            ]);

            $count = History::count();

            $history = History::where('user_id', $_SESSION['user'])->orderBy('id', 'desc')->take($count)->skip(8)->get();

            foreach($history as $die) {
                History::where('id', $die->id)->delete();
            }

            return $this->view->render($response, 'templates/quiz.twig', [
                'author' => $args['name'],
                'title' => $title,
                'questions' => $questions,
                'randomizeQuestions' => $randomizeQuestions,
                'randomizeAnswers' => $randomizeAnswers,
                'multipleChoice' => $multipleChoice,
                'slideQuestions' => $slideQuestions,
                'url' => $url,
            ]);
        }

        $this->flash->addMessage('error', 'Wrong quiz path.');

        return $response->withRedirect($this->router->pathFor('home'));
    }
}