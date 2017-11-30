<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\History;
use App\Models\Statistic;
use App\Controllers\Controller;

class QuizDeleteController extends Controller {
    public function deleteQuiz($request, $response, $args) {
        $user = User::where('id', $_SESSION['user'])->first();
        $quizId = explode('.', $args['url']);

        $quiz = Quiz::where('id', $quizId[1])->first();

        if ($quiz->getAttribute('user_id') === $_SESSION['user']) {
            Statistic::where('quiz_id', $quizId[1])->delete();
            History::where('quiz_id', $quizId[1])->delete();
            Answer::where('quiz_id', $quizId[1])->delete();
            Quiz::where('id', $quizId[1])->delete();

            $this->flash->addMessage('info', 'Quiz został usunięty.');
        }

        return $response->withRedirect($this->router->pathFor('myQuizzes'));
    }
}