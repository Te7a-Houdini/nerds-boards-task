<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use function GuzzleHttp\json_encode;
use App\Question;

class StoreStackOverflowQuestionsWithAnswers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client([
            'base_uri' => 'https://api.stackexchange.com/2.2/',
        ]);

        $response = $client->get('questions', [
            'query' => [
                'site' => 'stackoverflow',
                'tagged' => 'php',
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $newQuestions = collect(json_decode($response->getBody()->getContents())->items)
                                    ->whereNotIn('question_id', Question::pluck('question_id'))
                                    ->map(function ($question) {
                                        return [
                                            'question_id' => $question->question_id,
                                            'title' => $question->title,
                                            'link' => $question->link,
                                        ];
                                    })->all();
        
            Question::insert($newQuestions);
        }
    }
}