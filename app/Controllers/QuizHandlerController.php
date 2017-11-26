<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Controllers\Controller;

class QuizHandlerController extends Controller {
    protected $allAnswers;
    protected $correct;

    protected $correctUserAnswers;
    protected $incorrectUserAnswers;
    protected $userAnswers;
    
    public function index($request, $response) {
        // response z redirectem, flash message (liczba zdobytych punktów)
        $this->checkAnswers($request);
        // echo '<pre>';
        // // var_dump($patternAnswers);
        // var_dump($answers);
        // var_dump(true === 'on');
        // echo '</pre>';
    }

    private function getPattern($request) {
        // funkcja pobierajaca pattern

        $url = $request->getParam('url');
        $pattern = json_decode(Quiz::where('URL', $url)->first()->getAttribute('body'), true);

        $this->allAnswers = 0;
        $this->correct = 0;

        foreach ($pattern as $question) {
            // count of all answers
            $this->allAnswers += count($question['answers']);

            foreach ($question['answers'] as $index => $answer) {
                //count of correct answers
                unset($answer['answer']);
                $this->correct += ($answer['isCorrect']) ? 1 : 0;

                // array with pattern answers
                $patternAnswers[$index]['answers'][] = $answer['isCorrect'];
            }
        }

        return $patternAnswers;
    }

    private function checkAnswers($request) {
        // funkcja sprawdzająca odpowiedzi
        $this->correctUserAnswers = 0;
        $this->userAnswers = $this->getUserAnswers($request);
        $pattern = $this->getPattern($request);

        foreach ($this->userAnswers as $index => $question) {
            foreach ($question['answers'] as $number => $answer) {
                if ($answer == $pattern[$index]['answers'][$number]) {
                    $this->correctUserAnswers += 1;
                } else {
                    $this->incorrectUserAnswers += 1;
                }
            }
        }

        $this->incorrectUserAnswers += $this->correct - $this->correctUserAnswers;

        echo '<pre>';
        var_dump($this->correctUserAnswers);
        var_dump($this->incorrectUserAnswers);
        var_dump($pattern);
        echo '</pre>';
    }

    private function getUserAnswers($request) {
        // formatowanie odpowiedzi z requesta, żeby można je było porównać z patternem
        $answers = $request->getParam('answers');

        foreach ($answers as $index => $something) {

            $answers[$index] = ['answers' => $something];
        }

        return $answers;
    }

    public function postAnswers() {
        // funkcja wrzucająca odpowiedzi do bazy
    }

}