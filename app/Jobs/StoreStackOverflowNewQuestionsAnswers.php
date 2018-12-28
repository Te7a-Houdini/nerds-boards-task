<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use App\Answer;

class StoreStackOverflowNewQuestionsAnswers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $questions;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($questions)
    {
        $this->questions = $questions;
    }

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

        $questionsIds = $this->questions->implode('question_id', ';');

 
        $response = $client->get('questions/' . $questionsIds .'/answers', [
            'query' => [
                'site' => 'stackoverflow',
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $answers = collect(json_decode($response->getBody()->getContents())->items)
                        ->map(function ($answer) {
                            return [
                                'question_id' => $this->questions->firstWhere('question_id', $answer->question_id)->id,
                                'answer_id' => $answer->answer_id,
                            ];
                        });

            Answer::insert($answers->all());
        }
    }
}
