<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Statistic;
use App\Controllers\Controller;

class StatisticsController extends Controller {
    public function index($request, $response) {
        $link = explode('/',$request->getUri()->getPath());
        $partOfLink = explode('.', $link[2]);

        $id = $partOfLink[1];
        $statistic = Statistic::where('quiz_id', $id)->first();

        if ($statistic === NULL) {
            $this->flash->addMessage('info', 'Nikt, jak dotąd, nie zrobił Twojego quizu.');
            return $response->withRedirect($this->router->pathFor('myQuizzes'));
        }

        $statId = $statistic->getAttribute('quiz_id');
        $creatorId = Quiz::where('id', $statId)->first()->getAttribute('user_id');
        
        if ($_SESSION['user'] !== $creatorId) {
            return $response->withRedirect($this->router->pathFor('home'));
        }

        $stats = Statistic::where('quiz_id', $id)->get();

        foreach($stats as $stat) {
            $names[] = User::where('id', $stat->getAttribute('user_id'))->first()->getAttribute('name');
            $userCorrect[] = $stat->getAttribute('user_correct');
            $userWrong[] = $stat->getAttribute('user_wrong');
            $correct[] = $stat->getAttribute('correct');
            $wrong[] = $stat->getAttribute('wrong');
            $allAnswers[] = $stat->getAttribute('all_answers');
            $created[] = $stat->getAttribute('updated_at');
        }
        
        return $this->view->render($response, 'templates/stats.twig', [
            'names' => $names,
            'userCorrect' => $userCorrect,
            'userWrong' => $userWrong,
            'correct' => $correct,
            'wrong' => $wrong,
            'allAnswers' => $allAnswers,
            'created' => $created,
        ]);
    }
}