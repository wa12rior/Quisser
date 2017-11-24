<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Controllers\Controller;

class QuizHandlerController extends Controller {
    public function index($request, $response) {
        // response z redirectem, flash message (liczba zdobytych punktów)
        echo '<pre>';
        var_dump($request->getParams());
        echo '</pre>';
    }

    public function getPattern() {
        // funkcja pobierajaca pattern
    }

    public function checkAnswers() {
        // funkcja sprawdzająca odpowiedzi
    }

    public function formatAnswers() {
        // formatowanie odpowiedzi z requesta, żeby można je było porównać z patternem
    }

    public function postAnswers() {
        // funkcja wrzucająca odpowiedzi do bazy
    }

}