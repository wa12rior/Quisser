<?php

namespace App\Controllers;
use App\Models\User;
use App\Models\Quiz;
use App\Models\History;
use Slim\Views\Twig as View;

class HomeController extends Controller {

    public function index($request, $response) {
        $quizzes = Quiz::orderBy('id', 'desc')->take(5)->get();

        $count = History::count();
        $history = History::where('user_id', $_SESSION['user'])->orderBy('id', 'desc')->take($count)->get();

        foreach ($history as $quiz) {
            $historyTitles[] = Quiz::where('id', $quiz->getAttribute('quiz_id'))->first()->getAttribute('title');
            $historySlugs[] = Quiz::where('id', $quiz->getAttribute('quiz_id'))->first()->getAttribute('URL');
            $historyAuthors[] =  User::where('id', Quiz::where('id', $quiz->getAttribute('quiz_id'))->first()->getAttribute('user_id'))->first()->getAttribute('name');
        }

        foreach ($quizzes as $quiz) {
            $titles[] = $quiz->getAttribute('title');
            $slugs[] = $quiz->getAttribute('URL');
            $authors[] = User::where('id', $quiz->getAttribute('user_id'))->first()->getAttribute('name');
        }
        
        return $this->view->render($response, 'home.twig', [
            'new' => [
                'authors' => $authors,
                'slugs' => $slugs,
                'titles' => $titles,
            ],
            'history' => [
                'authors' => $historyAuthors,
                'slugs' => $historySlugs,
                'titles' => $historyTitles,
            ]
        ]);
    }
}