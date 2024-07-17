<?php

namespace App\Http\Controllers;

use App\Services\QuizService;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function categories()
    {
        $questions = $this->quizService->fetchQuestions();
        $categories = collect($questions)->pluck('category')->unique();

        return view('quiz.categories', compact('categories'));
    }

    public function quiz($category)
    {
        $questions = $this->quizService->fetchQuestions();
        $filteredQuestions = collect($questions)->filter(function($question) use ($category) {
            return $question['category'] === $category;
        })->values()->all();
        $formattedQuestions = array_map(function($question) {
            $answers = $question['incorrectAnswers'];
            $answers[] = $question['correctAnswer'];
            shuffle($answers); 
            return array_merge($question, ['answers' => $answers]);
        }, $filteredQuestions);

        return view('quiz.index', compact('formattedQuestions', 'category'));
    }

    public function submit(Request $request)
    {
        $answers = $request->input('answers');

        return view('quiz.results', compact('score'));
    }
}
