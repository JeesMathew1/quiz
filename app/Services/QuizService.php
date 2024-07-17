<?php

namespace App\Services;

use GuzzleHttp\Client;

class QuizService
{
    public function fetchQuestions()
    {
        $client = new Client();
        $response = $client->get('https://the-trivia-api.com/api/questions');
        return json_decode($response->getBody(), true);
    }
}
