<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Statistic;
use App\Controllers\Controller;

class QuizHandlerController extends Controller {
    protected $allAnswers;
    protected $correct;

    protected $quizId;

    protected $correctUserAnswers;
    protected $incorrectUserAnswers;
    protected $userAnswers;

    protected $equal;
    protected $difference;
    
    public function index($request, $response) {
        $this->checkAnswers($request);
        $this->postStatistics();
        $this->postAnswers();

        
        $this->flash->addMessage('info', $this->correctUserAnswers . ' odpowiedzi poprawnych, ' . $this->incorrectUserAnswers . ' błędów. Sprawdź więcej szczegółów w zakładce Moje Quizy.');

        return $response->withRedirect($this->router->pathFor('home'));
        // response z redirectem, flash message (liczba zdobytych punktów)
    }

    private function getPattern($request) {
        // funkcja pobierajaca pattern

        $url = $request->getParam('url');
        $pattern = json_decode(Quiz::where('URL', $url)->first()->getAttribute('body'), true);

        $this->quizId = Quiz::where('URL', $url)->first()->getAttribute('id');

        $this->allAnswers = 0;
        $this->correct = 0;

        foreach ($pattern as $index => $question) {
            // count of all answers
            $this->allAnswers += count($question['answers']);

            foreach ($question['answers'] as  $answer) {

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
        $this->incorrectUserAnswers = 0;
        $this->userAnswers = $this->getUserAnswers($request);
        ksort($this->userAnswers);
        $pattern = $this->getPattern($request);

        foreach ($this->userAnswers as $index => $answers) {
            $new[] = array_map(function($value) {
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }, $answers['answers']);

            $this->userAnswers[$index] = [
                'answers' => $new[$index]
            ];
        }  

        foreach ($this->userAnswers as $index => $question) {
            foreach ($question['answers'] as $number => $answer) {
                if ($answer == $pattern[$index]['answers'][$number]) {
                    if ($answer) {
                        $this->correctUserAnswers += 1;
                    }
                } else {
                    $this->incorrectUserAnswers += 1;
                }
            }

            $this->equal[] = array_intersect_assoc($pattern[$index]['answers'], $question['answers']);
            $this->difference[] = array_diff_assoc($pattern[$index]['answers'],  $question['answers']);
        }
    }

    private function getUserAnswers($request) {
        // formatowanie odpowiedzi z requesta, żeby można je było porównać z patternem
        $answers = $request->getParam('answers');

        if ($request->getParam('mtp') == '0') {
            foreach ($answers as $index => $answer) {
                $answers[$index] = ['answers' => [
                    $answer => true
                ]];
            }

            return $answers;
        }

        foreach ($answers as $index => $something) {

            $answers[$index] = ['answers' => $something];
        }

        return $answers;
    }

    public function postAnswers() {
        // funkcja wrzucająca odpowiedzi do bazy
        
        $uAnswers = Answer::create([
            'user_id' => $_SESSION['user'],
            'quiz_id' => $this->quizId,
            'body' => json_encode($this->equal),
            'body_bad' => json_encode($this->difference),
        ]);
    }

    public function postStatistics() {
        $statistic = Statistic::create([
            'user_id' => $_SESSION['user'],
            'quiz_id' => $this->quizId,
            'user_correct' => $this->correctUserAnswers,
            'user_wrong' => $this->incorrectUserAnswers,
            'correct' => $this->correct,
            'wrong' => ($this->allAnswers - $this->correct),
            'all_answers' => $this->allAnswers,
        ]);
    }
}